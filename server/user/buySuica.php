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

$vm->buySuicaVm($user, $drinkArray, $db);
