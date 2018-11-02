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
if($drinkArray == null){
  $drinkArray =array();
}

//$drinkTableArrayの作成
$drinkTableArray = array();
$drinkInfo = $_SESSION['drinkInfo'];
foreach ($drinkInfo as $value) {
  $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
}

$error = "";
if($_POST["addDrinkCount"] == ""){
  $error .= "個数を入力してください<br/>";
}

if(!$error == ""){
  echo $error;
  return;
}

$isUpdated = false;
foreach ($drinkArray as $drinkId => $drink) {
  if($drinkId == $_POST["addedExistingDrink"]){
    echo "すでに同じ商品が存在しています！変更の場合は変更フォームを利用してください<br/>";
    $isUpdated = true;
    break;
  }
}
if(!$isUpdated){
  $drink = $drinkTableArray[$_POST["addedExistingDrink"]];
  $drinkName = $drink->getName();
  $drinkPrice = $drink->getPrice();
  $drinkArray[$_POST["addedExistingDrink"]] = new Drink(
    $drinkName,
    $drinkPrice
  );
  $db->beginTransaction();
  $db->exec("insert into vending_machine_drink (vending_machine_id, drink_id, drink_count) values (" . $vm->getId() . ", " . $_POST["addedExistingDrink"] . ", " . $_POST["addDrinkCount"] . ")");
  $db->commit();
  echo "追加しました！<br/>";
}




 ?>
