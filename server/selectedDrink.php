<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

$userId = $_SESSION[$_POST['userEncrypt']];
$vm = $_SESSION[$userId . 'SES_KEY_VM'];
$user = $_SESSION[$userId . 'SES_KEY_USER'];

// 自販機のdrinkArray,stockArrayの取得
$drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
$vm->setDrinkArray($db, $drink_in_vending_machine);
$drinkArray = $vm->getDrinks();

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
