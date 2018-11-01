<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$stmt = $db->query("select * from vending_machine where id = " . $_POST['choicedVmId']);
$vmRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
$vm = new VendingMachine($vmRecord[0]['id'], $vmRecord[0]['name'], $vmRecord[0]['type'], $vmRecord[0]['cash'], $vmRecord[0]['suica'], $vmRecord[0]['charge']);

$userId = $_SESSION[$_POST['userEncrypt']];
$stmt = $db->query("select * from users where id = " . $userId);
$userRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user = new User($userRecord[0]['id'], $userRecord[0]['name'], $userRecord[0]['cash'], $userRecord[0]['suica']);

// 自販機のdrinkArray,stockArrayの取得
$stmt = $db->query("select * from vending_machine_drink where vending_machine_id = " . $vm->getId());
$drink_in_vending_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
$vm->setDrinkArray($db, $drink_in_vending_machine);
$drinkArray = $vm->getDrinks();

// ユーザーのドリンクアレイ取得
$user->setDrinkArray($userId, $db);

foreach ($drinkArray as $drinkId => $drink){
  if($_POST['selectedDrink'] == $drinkId){
    switch ($vm->getType()){
      case VendingMachine::$vm_type_Cash:
        $vm->buyCashVm($user, $drink, $drinkId, $db);
        break;
      case VendingMachine::$vm_type_Suica:
        $vm->choiceSuicaDrink($drink, $drinkId);
        break;
      case VendingMachine::$vm_type_Both:
        if($vm->getCharge() > 0){
          $vm->buyCashVm($user, $drink, $drinkId, $db);
        }elseif($vm->getCharge() == 0){
          $vm->choiceSuicaDrink($drink, $drinkId);
        }
        break;
    }
    break;
  }
}
