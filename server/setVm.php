<?php
require_once("./tool.php");
$userId = $_SESSION[$_POST['userEncrypt']];
if($userId == null){
  echo "ログインしてください<br/>";
  $html .= "<button id='gologin'>ログインする</button>";
  echo $html;
  return;
}

$html = displayAddVendingMachineButton();

$html .= "<button id='back_vm_top'>戻る</button></br>";
echo $html;
 ?>
