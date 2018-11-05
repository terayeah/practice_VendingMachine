<?php
require_once("./define.php");
session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$stmt = $db->query("select * from users where name = '" . $_POST['username'] . "'");
$userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
$userId = $userInfo[0]['id'];
$userEncrypt = $userInfo[0]['encrypted_password'];
$_SESSION[$userEncrypt] = $userId;

$rs = array(
    "encrypted_password" => htmlspecialchars($userEncrypt)
);

if($userInfo != null){
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode($rs);
}
