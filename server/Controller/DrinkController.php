<?php

class DrinkController{

  public static function makeProduct($drinkName, $drinkPrice){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    if($drinkName == ""){
      return array("message" => "商品名を入力してください");
    }
    if($drinkPrice == ""){
      return array("message" => "価格を入力してください");
    }
    $db->exec("insert into drink (name, price) values ('" . $drinkName . "', " . $drinkPrice . ")");
    return array("message" => "新規作成！");
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
         $db->beginTransaction();
         $db->exec("update drink set name = '" . $changeProductName . "' where id = " . $drinkId);
         $db->exec("update drink set price = " . $changeProductPrice . " where id = " . $drinkId);
         $db->commit();
         return array("message" => "名称と価格を変更しました");
         break;
       }elseif(!$changeProductName && $changeProductPrice){
         $db->exec("update drink set price = " . $changeProductPrice . " where id = " . $drinkId);
         return array("message" => "価格を変更しました");
         break;
       }elseif($changeProductName && !$changeProductPrice){
         $db->exec("update drink set name = '" . $changeProductName . "' where id = " . $drinkId);
         return array("message" => "名称を変更しました");
         break;
       }else{
         return array("message" => "変更がありません");
         break;
       }
     }
    }
  }


}
