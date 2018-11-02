<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

// 選択した自販機の取得
$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$stmt = $db->query("select * from vending_machine where id = " . $_POST['selectedVmId']);
$vmRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
$vm = new VendingMachine($vmRecord[0]['id'], $vmRecord[0]['name'], $vmRecord[0]['type'], $vmRecord[0]['cash'], $vmRecord[0]['suica'], $vmRecord[0]['charge']);

// 自販機のdrinkArray,stockArrayの取得
$stmt = $db->query("select * from vending_machine_drink where vending_machine_id = " . $vm->getId());
$drink_in_vending_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
$vm->setDrinkArray($db, $drink_in_vending_machine);
$drinkArray = $vm->getDrinks();

//$drinkTableArrayの作成
$drinkTableArray = array();
$stmt = $db->query("select * from drink");
$drinkInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($drinkInfo as $value) {
  $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
}


$html .= "<h2>自販機 : " . $vm->getName() . "</h2>";

// ドリンク追加ボタン
$html .= displayAddDrinkForm($drinkTableArray);
// ドリンク変更ボタン
$html .= displayChangeDrinkForm($drinkArray);
// ドリンク削除ボタン
$html .= displayDeleteDrinkForm($drinkArray);

$html .= "<h2>商品開発</h2>";
// 商品作成ボタン
$html .= displayMakeProductForm();
// 商品変更ボタン
$html .= displayChangeProductForm($drinkTableArray);

$html .= "<button id='back_vm_view'>戻る</button></br>";

$rs = array(
    "html" => $html,
    "vmId" => htmlspecialchars($vmRecord[0]['id'])
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($rs);
