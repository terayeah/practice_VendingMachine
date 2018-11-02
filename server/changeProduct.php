<?php
require_once("./define.php");
require_once("./VendingMachine.php");
require_once("./User.php");
require_once("./Drink.php");
require_once("./tool.php");
session_start();

$db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
//$drinkTableArrayの作成
$drinkTableArray = array();
$stmt = $db->query("select * from drink");
$drinkInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($drinkInfo as $value) {
  $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
}

foreach ($drinkTableArray as $drinkId => $drink) {
 if($drinkId == $_POST['changedProduct']){
   if ($_POST['changeProductName'] && $_POST['changeProductPrice']){
     echo "名称と価格を変更しました<br/>";
     $db->beginTransaction();
     $db->exec("update drink set name = '" . $_POST['changeProductName'] . "' where id = " . $drinkId);
     $db->exec("update drink set price = " . $_POST['changeProductPrice'] . " where id = " . $drinkId);
     $db->commit();
     break;
   }elseif(!$_POST['changeProductName'] && $_POST['changeProductPrice']){
     echo "価格を変更しました<br/>";
     $db->exec("update drink set price = " . $_POST['changeProductPrice'] . " where id = " . $drinkId);
     break;
   }elseif($_POST['changeProductName'] && !$_POST['changeProductPrice']){
     echo "名称を変更しました<br/>";
     $db->exec("update drink set name = '" . $_POST['changeProductName'] . "' where id = " . $drinkId);
     break;
   }else{
     echo "変更がありません<br/>";
     break;
   }
 }
}
