<?php
require_once("./define.php");
require_once("./VendingMachine.php");
session_start();
$userId = $_SESSION[$_POST['userEncrypt']];
if($userId == null){
  echo "ログインしてください<br/>";
  $html .= "<button id='logout'>ログインする</button>";
  echo $html;
  return;
}

unset($_SESSION[$userId . 'SES_KEY_VM']);
unset($_SESSION[$userId . 'SES_KEY_USER']);
unset($_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD']);
unset($_SESSION["choice"]);

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$stmt = $db->query("select * from vending_machine");
$vmArray = $stmt->fetchAll(PDO::FETCH_ASSOC);


foreach($vmArray as $vm){
  if ($vm['type'] == VendingMachine::$vm_type_Cash){
    $cashButton .= "<button class='selectedVm' value='" . $vm['id'] . "'>" . $vm['name'] . "</button></br>";
  }
  if ($vm['type'] == VendingMachine::$vm_type_Suica){
    $suicaButton .= "<button class='selectedVm' value='" . $vm['id'] . "'>" . $vm['name'] . "</button></br>";
  }
  if ($vm['type'] == VendingMachine::$vm_type_Both){
    $bothButton .= "<button class='selectedVm' value='" . $vm['id'] . "'>" . $vm['name'] . "</button></br>";
  }
}
$html = "<h3>現金会計のみの自販機</h3>";
$html .= $cashButton;
$html .= "<h3>Suica会計のみの自販機</h3>";
$html .= $suicaButton;
$html .= "<h3>現金とSuica両方会計の自販機</h3>";
$html .= $bothButton;
$html .= "<br>";
$html .= "<button id='addVm'>自販機を追加する</button>";
$html .= "<br>";
$html .= "<button id='logout'>サインアウト</button>";

echo $html;
