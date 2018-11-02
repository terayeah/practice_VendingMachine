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

$error = "";
if($_POST["changeDrinkStock"] == ""){
  $error .= "個数を入力してください<br/>";
}

if(!$error == ""){
  echo $error;
  return;
}

$isChecked = false;
if($_POST['changeDrinkStock'] == null){
  echo "変更がありません<br/>";
  $isChecked = true;
}
if(!$isChecked){
  foreach ($drinkArray as $drinkId => $drink) {
    if($drinkId == $_POST['changedDrink']){
      $drink = $drinkTableArray[$_POST['changedDrink']];
      $drinkName = $drink->getName();
      $vm->setStock($drinkName, $_POST['changeDrinkStock']);
      $db->beginTransaction();
      $db->exec("update vending_machine_drink set drink_count = " . $_POST['changeDrinkStock'] . " where vending_machine_id = " . $vm->getId() . " and drink_id = " . $drinkId);
      $db->commit();
      echo "在庫を変更しました<br/>";
      break;
    }
  }
}


 ?>
