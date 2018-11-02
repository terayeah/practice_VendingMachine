<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

$error = "";
if($_POST["drinkName"] == ""){
  $error .= "商品名を入力してください<br/>";
}
if($_POST["drinkPrice"] == ""){
  $error .= "価格を入力してください<br/>";
}

if(!$error == ""){
  echo $error;
  return;
}
$db->beginTransaction();
$db->exec("insert into drink (name, price) values ('" . $_POST["drinkName"] . "', " . $_POST["drinkPrice"] . ")");
$db->commit();
echo "新規作成！<br/>";
