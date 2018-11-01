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

//ログインユーザーの取得
$userId = $_SESSION[$_POST['userEncrypt']];
$stmt = $db->query("select * from users where id = " . $userId);
$userRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
$user = new User($userRecord[0]['id'], $userRecord[0]['name'], $userRecord[0]['cash'], $userRecord[0]['suica']);

// 商品データの取得
if(!isset($_SESSION['drinkInfo'])){
  $stmt = $db->query("select * from drink");
  $drinkInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $_SESSION['drinkInfo'] = $drinkInfo;
}
$drinkInfo = $_SESSION['drinkInfo'];

// 自販機のdrinkArray,stockArrayの取得
$stmt = $db->query("select * from vending_machine_drink where vending_machine_id = " . $vm->getId());
$drink_in_vending_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
$vm->setDrinkArray($db, $drink_in_vending_machine);

// ユーザーのドリンクアレイ取得
$user->setDrinkArray($userId, $db);

$html .= "<h2>自販機 : " . $vm->getName() . "</h2>";

// headerの作成
$html .= displayHeader($vm);
// 入金フォームの作成
$html .= displayCashSlot($vm);
// チャージフォームの作成
$html .= displaySuicaChargeMachine($vm);
// ドリンクボタンの作成
$html .= displayDrinkButton($vm);
// suica購入ボタン
$html .= displayBuyButton($vm);
// footerの作成
$html .= displayFooter($user, $drinkInfo);

$html .= "<button id='setDrink'>商品を編集する</button></br>";

$html .= "<button id='back_vm_top'>戻る</button></br>";

$rs = array(
    "html" => $html,
    "vmId" => htmlspecialchars($vmRecord[0]['id'])
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($rs);
