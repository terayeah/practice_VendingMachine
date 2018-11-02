<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

// 選択した自販機の取得
$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$userId = $_SESSION[$_POST['userEncrypt']];
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
  if($drinkId == $_POST['deletedDrink']){
    $drink = $drinkTableArray[$_POST['deletedDrink']];
    $drinkName = $drink->getName();
    unset($drinkArray[$drinkId]);
    $vm->unsetDrinkStock($drinkName);
    $db->beginTransaction();
    $db->exec("delete from vending_machine_drink where vending_machine_id = " . $vm->getId() . " and drink_id = " . $drinkId );
    $db->commit();
    echo "削除完了！<br/>";
    break;
  }
}
