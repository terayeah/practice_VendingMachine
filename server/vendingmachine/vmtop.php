<?php
require_once("./define.php");
require_once("./VendingMachine.php");
session_start();
$userId = $_SESSION[$_POST['userEncrypt']];

unset($_SESSION[$userId . 'SES_KEY_VM']);
unset($_SESSION[$userId . 'SES_KEY_USER']);
unset($_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD']);
unset($_SESSION["choice"]);

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$stmt = $db->query("select * from vending_machine");
$vmTableArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($vmTableArray as $vmRecord) {
  $vm = new VendingMachine($vmRecord['id'], $vmRecord['name'], $vmRecord['type'], $vmRecord['cash'], $vmRecord['suica'], $vmRecord['charge']);
  $vmArray[$vmRecord['id']] = $vm->getJsonArray();
}

$rs = array(
    "vmArray" => $vmArray
  );
header('Content-Type: application/json; charset=utf-8');
echo json_encode($rs);
