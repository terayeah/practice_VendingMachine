<?php

class VendingMachineController{

  public static function drowvmtop($userEncrypt){
    $userId = $_SESSION[$userEncrypt];
    unset($_SESSION[$userId . 'SES_KEY_VM']);
    unset($_SESSION[$userId . 'SES_KEY_USER']);
    unset($_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD']);
    unset($_SESSION["choice"]);
    $vmdb = new VendingMachineMapper();
    $vmTableArray = $vmdb->selectAll();
    foreach ($vmTableArray as $vmRecord) {
      $vm = new VendingMachine($vmRecord['id'], $vmRecord['name'], $vmRecord['type'], $vmRecord['cash'], $vmRecord['suica'], $vmRecord['charge']);
      $vmArray[$vmRecord['id']] = $vm->getJsonArray();
    }
    return array(
        "vmArray" => $vmArray
    );
  }

  public static function drowvmview($userEncrypt, $selectedVmId){
    $userdb = new UserMapper();
    $vmdb = new VendingMachineMapper();
    $drinkdb = new DrinkMapper();
    $vmdrinkdb = new VendingMachineDrinkMapper();
    $userdrinkdb = new UserDrinkMapper();
    //ログインユーザーの取得
    $userId = $_SESSION[$userEncrypt];
    $userRecord = $userdb->selectFromId($userId);
    $user = new User($userRecord[0]['id'], $userRecord[0]['name'], $userRecord[0]['cash'], $userRecord[0]['suica']);
    $_SESSION[$userId . 'SES_KEY_USER'] = $user;
    // 選択した自販機の取得
    $vmRecord = $vmdb->selectFromId($selectedVmId);
    $vm = new VendingMachine($vmRecord[0]['id'], $vmRecord[0]['name'], $vmRecord[0]['type'], $vmRecord[0]['cash'], $vmRecord[0]['suica'], $vmRecord[0]['charge']);
    $_SESSION[$userId . 'SES_KEY_VM'] = $vm;
    // 商品データの取得
    if(!isset($_SESSION['drinkInfo'])){
      $drinkInfo = $drinkdb->selectAll();
      $_SESSION['drinkInfo'] = $drinkInfo;
    }
    $drinkInfo = $_SESSION['drinkInfo'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $vmdrinkdb->selectFromVmId($vm->getId());
    $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'] = $drink_in_vending_machine;
    $vm->setDrinkArray($drink_in_vending_machine);
    // ユーザーのドリンクアレイ取得
    $user_drink = $userdrinkdb->selectFromUserId($userId);
    $user->setDrinkArray($db, $user_drink);

    $user_drink_array = array();
    foreach ($user->getDrinkArray() as $drinkId => $howMany) {
      foreach ($drinkInfo as $drink_record) {
        if($drink_record['id'] == $drinkId){
          $drinkName = $drink_record['name'];
          $user_drink_array[$drinkName] = $howMany;
        }
      }
    }

    return array(
        "vm" => $vm->getJsonArray(),
        "user" => $user->getJsonArray(),
        "vm_drink" => $vm->getDrinksJsonArray(),
        "vm_stock" => $vm->getStockArray(),
        "user_drink" => $user_drink_array
    );
  }

  public static function setDrink($selectedVmId){
    $vmdb = new VendingMachineMapper();
    $vmdrinkdb = new VendingMachineDrinkMapper();
    $drinkdb = new DrinkMapper();
    // 選択した自販機の取得
    $vmRecord = $vmdb->selectFromId($selectedVmId);
    $vm = new VendingMachine($vmRecord[0]['id'], $vmRecord[0]['name'], $vmRecord[0]['type'], $vmRecord[0]['cash'], $vmRecord[0]['suica'], $vmRecord[0]['charge']);
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $vmdrinkdb->selectFromVmId($vm->getId());
    $vm->setDrinkArray($drink_in_vending_machine);
    $drinkArray = $vm->getDrinksJsonArray();
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $drinkdb->selectAll();
    $_SESSION['drinkInfo'] = $drinkInfo;

    foreach ($drinkInfo as $value) {
      $drink = new Drink($value['name'], $value['price']);
      $drinkTableArray[$value['id']] = $drink->getJsonArray();
    }

    return array(
        "name" => $vm->getName(),
        "drinks" => array(
          "vendingMachine"=>$drinkArray,
          "all"=>$drinkTableArray
        )
    );
  }

  public static function addVm($vmType, $vmName){
    $vmdb = new VendingMachineMapper();
    if(empty($vmName)){
      return array("error" => "名前を入力してください");
    }else{
      $name = $vmName;
      $type = $vmType;
      $vmdb->insertVendingMachine($name, $type);
      return array("message" => "新規作成！");
    }
  }

  public static function addDrink($userEncrypt, $addedExistingDrink, $addDrinkCount){
    // 選択した自販機の取得
    $vmdrinkdb = new VendingMachineDrinkMapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();
    if($drinkArray == null){
      $drinkArray = array();
    }
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $_SESSION['drinkInfo'];
    foreach ($drinkInfo as $value) {
      $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
    }
    if($addDrinkCount==""){
      return array("error" => "個数を入力してください");
    }
    $isUpdated = false;
    foreach ($drinkArray as $drinkId => $drink) {
      if($drinkId == $addedExistingDrink){
        $isUpdated = true;
        return array("error" => "すでに同じ商品が存在しています");
        break;
      }
    }
    if(!$isUpdated){
      $drink = $drinkTableArray[$addedExistingDrink];
      $drinkName = $drink->getName();
      $drinkPrice = $drink->getPrice();
      $drinkArray[$addedExistingDrink] = new Drink(
        $drinkName,
        $drinkPrice
      );
      $vmdrinkdb->insertVendingMachineDrinkCount($vm->getId(), $addedExistingDrink, $addDrinkCount);
      return array("message" => "追加しました");
    }
  }

  public static function changeDrink($userEncrypt, $changedDrink, $changeDrinkStock){
    $vmdrinkdb = new VendingMachineDrinkMapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $_SESSION['drinkInfo'];
    foreach ($drinkInfo as $value) {
      $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
    }
    if($changeDrinkStock == ""){
      return array("error" => "個数を入力してください");
    }
    $isChecked = false;
    if($changeDrinkStock == null){
      $isChecked = true;
      return array("error" => "変更がありません");
    }
    if(!$isChecked){
      foreach ($drinkArray as $drinkId => $drink) {
        if($drinkId == $changedDrink){
          $drink = $drinkTableArray[$changedDrink];
          $drinkName = $drink->getName();
          $vm->setStock($drinkName, $changeDrinkStock);
          $vmdrinkdb->updateVendingMachineDrinkCount($vm->getId(), $drinkId, $changeDrinkStock);
          return array("message" => "在庫を変更しました");
          break;
        }
      }
    }
  }

  public static function deleteDrink($userEncrypt, $deletedDrink){
    $vmdrinkdb = new VendingMachineDrinkMapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $_SESSION['drinkInfo'];
    foreach ($drinkInfo as $value) {
      $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
    }

    foreach ($drinkArray as $drinkId => $drink) {
      if($drinkId == $deletedDrink){
        $drink = $drinkTableArray[$deletedDrink];
        $drinkName = $drink->getName();
        unset($drinkArray[$drinkId]);
        $vm->unsetDrinkStock($drinkName);
        $vmdrinkdb->deleteVendignMachineDrinkCount($vm->getId(), $drinkId);
        return array("message" => "削除完了！");
        break;
      }
    }
  }
}
