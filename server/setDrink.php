<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");

session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

// 選択した自販機の取得
$stmt = $db->query("select * from vending_machine where id = " . $_POST['selectedVmId']);
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

$rs = array(
    "name" => $vm->getName(),
    "drinks" => array(
      "vendingMachine"=>$drinkArray,
      "all"=>$drinkTableArray
    ),
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($rs);
