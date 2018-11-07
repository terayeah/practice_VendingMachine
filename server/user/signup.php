<?php
require_once("./define.php");
$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
$stmt = $db->query("select * from users");
$userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
function createSalt($length = 8){
  return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

$isUpdated = false;
if($_POST['newUsername'] !=  "" && $_POST['newPassword'] !=  ""){
  foreach($userInfo as $user){
    if($user['name'] == $_POST['newUsername']){
      $isUpdated = true;
    }
  }
  if(!$isUpdated){
    $salt = createSalt();
    $encrypted_password = crypt($_POST['newPassword'], $salt);
    $db->exec("insert into users (name, cash, suica, salt, encrypted_password) values ('" . $_POST['newUsername'] . "', 5000, 2000, '" . $salt . "', '" . $encrypted_password . "')");
  }
}

$rs = array(
    "isUpdated" => $isUpdated
);
header('Content-Type: application/json; charset=utf-8');
echo json_encode($rs);
