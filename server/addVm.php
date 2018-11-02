<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

$error = "";
if($_POST['vmName'] == ""){
  $error .= "自販機名を入力してください<br/>";
}

if($error != ""){
  echo $error;
  return;
}

$name = $_POST['vmName'];
$type = $_POST['vmType'];
$db->beginTransaction();
$db->exec("insert into vending_machine (name, type) values ('" . $name . "', '" . $type . "')");
$db->commit();
echo "新規作成！<br/>";
