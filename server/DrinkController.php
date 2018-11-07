<?php
require_once("define.php");

class DrinkController{

  public static function makeProduct($drinkName, $drinkPrice){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $error = "";
    if($drinkName == ""){
      $error .= "商品名を入力してください<br/>";
    }
    if($drinkPrice == ""){
      $error .= "価格を入力してください<br/>";
    }
    if(!$error == ""){
      echo $error;
      return;
    }
    $db->exec("insert into drink (name, price) values ('" . $drinkName . "', " . $drinkPrice . ")");
    echo "新規作成！<br/>";
  }

  public static function changeProduct($changedProduct, $changeProductName, $changeProductPrice){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $_SESSION['drinkInfo'];
    foreach ($drinkInfo as $value) {
      $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
    }
    foreach ($drinkTableArray as $drinkId => $drink) {
     if($drinkId == $changedProduct){
       if ($changeProductName && $changeProductPrice){
         echo "名称と価格を変更しました<br/>";
         $db->beginTransaction();
         $db->exec("update drink set name = '" . $changeProductName . "' where id = " . $drinkId);
         $db->exec("update drink set price = " . $changeProductPrice . " where id = " . $drinkId);
         $db->commit();
         break;
       }elseif(!$changeProductName && $changeProductPrice){
         echo "価格を変更しました<br/>";
         $db->exec("update drink set price = " . $changeProductPrice . " where id = " . $drinkId);
         break;
       }elseif($changeProductName && !$changeProductPrice){
         echo "名称を変更しました<br/>";
         $db->exec("update drink set name = '" . $changeProductName . "' where id = " . $drinkId);
         break;
       }else{
         echo "変更がありません<br/>";
         break;
       }
     }
    }
  }


}
