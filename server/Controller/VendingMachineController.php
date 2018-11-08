<?php
require_once("/opt/local/www/apache2/html/lessons/a_vending_machine/server/define.php");

class VendingMachineController{

  public static function drowvmtop($userEncrypt){
    $userId = $_SESSION[$userEncrypt];
    unset($_SESSION[$userId . 'SES_KEY_VM']);
    unset($_SESSION[$userId . 'SES_KEY_USER']);
    unset($_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD']);
    unset($_SESSION["choice"]);
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $stmt = $db->query("select * from vending_machine");
    $vmTableArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($vmTableArray as $vmRecord) {
      $vm = new VendingMachine($vmRecord['id'], $vmRecord['name'], $vmRecord['type'], $vmRecord['cash'], $vmRecord['suica'], $vmRecord['charge']);
      $vmArray[$vmRecord['id']] = $vm->getJsonArray();
    }
    return array(
        "vmArray" => $vmArray
    );
  }

  public static function drowvmview($userEncrypt, $selectedVmId){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    //ログインユーザーの取得
    $userId = $_SESSION[$userEncrypt];
    $stmt = $db->query("select * from users where id = " . $userId);
    $userRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user = new User($userRecord[0]['id'], $userRecord[0]['name'], $userRecord[0]['cash'], $userRecord[0]['suica']);
    $_SESSION[$userId . 'SES_KEY_USER'] = $user;
    // 選択した自販機の取得
    $stmt = $db->query("select * from vending_machine where id = " . $selectedVmId);
    $vmRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $vm = new VendingMachine($vmRecord[0]['id'], $vmRecord[0]['name'], $vmRecord[0]['type'], $vmRecord[0]['cash'], $vmRecord[0]['suica'], $vmRecord[0]['charge']);
    $_SESSION[$userId . 'SES_KEY_VM'] = $vm;
    // 商品データの取得
    if(!isset($_SESSION['drinkInfo'])){
      $stmt = $db->query("select * from drink");
      $drinkInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
      $_SESSION['drinkInfo'] = $drinkInfo;
    }
    $drinkInfo = $_SESSION['drinkInfo'];
    // 自販機のdrinkArray,stockArrayの取得
    $stmt = $db->query("select * from vending_machine_drink where vending_machine_id = " . $vm->getId());
    $drink_in_vending_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'] = $drink_in_vending_machine;
    $vm->setDrinkArray($db, $drink_in_vending_machine);
    // ユーザーのドリンクアレイ取得
    $stmt = $db->query("select * from user_drink where user_id = " . $userId);
    $user_drink = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        "vm_stock" => $vm->getStock(),
        "user_drink" => $user_drink_array
    );
  }

  public static function setDrink($selectedVmId){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    // 選択した自販機の取得
    $stmt = $db->query("select * from vending_machine where id = " . $selectedVmId);
    $vmRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $vm = new VendingMachine($vmRecord[0]['id'], $vmRecord[0]['name'], $vmRecord[0]['type'], $vmRecord[0]['cash'], $vmRecord[0]['suica'], $vmRecord[0]['charge']);
    // 自販機のdrinkArray,stockArrayの取得
    $stmt = $db->query("select * from vending_machine_drink where vending_machine_id = " . $vm->getId());
    $drink_in_vending_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $vm->setDrinkArray($db, $drink_in_vending_machine);
    $drinkArray = $vm->getDrinksJsonArray();
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $stmt = $db->query("select * from drink");
    $drinkInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    if(empty($vmName)){
      return array("error" => "名前を入力してください");
    }else{
      $name = $vmName;
      $type = $vmType;
      $db->exec("insert into vending_machine (name, type) values ('" . $name . "', '" . $type . "')");
      return array("message" => "新規作成！");
    }
  }

  public static function addDrink($userEncrypt, $addedExistingDrink, $addDrinkCount){
    // 選択した自販機の取得
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($db, $drink_in_vending_machine);
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
      return array("message" => "個数を入力してください");
    }
    $isUpdated = false;
    foreach ($drinkArray as $drinkId => $drink) {
      if($drinkId == $addedExistingDrink){
        $isUpdated = true;
        return array("message" => "すでに同じ商品が存在しています");
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
      $db->exec("insert into vending_machine_drink (vending_machine_id, drink_id, drink_count) values (" . $vm->getId() . ", " . $addedExistingDrink . ", " . $addDrinkCount . ")");
      return array("message" => "追加しました");
    }
  }

  public static function changeDrink($userEncrypt, $changedDrink, $changeDrinkStock){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($db, $drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $_SESSION['drinkInfo'];
    foreach ($drinkInfo as $value) {
      $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
    }
    if($changeDrinkStock == ""){
      return array("message" => "個数を入力してください");
    }
    $isChecked = false;
    if($changeDrinkStock == null){
      $isChecked = true;
      return array("message" => "変更がありません");
    }
    if(!$isChecked){
      foreach ($drinkArray as $drinkId => $drink) {
        if($drinkId == $changedDrink){
          $drink = $drinkTableArray[$changedDrink];
          $drinkName = $drink->getName();
          $vm->setStock($drinkName, $changeDrinkStock);
          $db->exec("update vending_machine_drink set drink_count = " . $changeDrinkStock . " where vending_machine_id = " . $vm->getId() . " and drink_id = " . $drinkId);
          return array("message" => "在庫を変更しました");
          break;
        }
      }
    }
  }

  public static function deleteDrink($userEncrypt, $deletedDrink){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($db, $drink_in_vending_machine);
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
        $db->exec("delete from vending_machine_drink where vending_machine_id = " . $vm->getId() . " and drink_id = " . $drinkId );
        return array("message" => "削除完了！");
        break;
      }
    }
  }
}
