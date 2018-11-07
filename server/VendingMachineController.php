<?php
require_once("define.php");

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
}
