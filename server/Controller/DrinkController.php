<?php

class DrinkController{

  public static function makeProduct($drinkName, $drinkPrice){
    $db = new Mapper();
    if($drinkName == ""){
      return array("message" => "商品名を入力してください");
    }
    if($drinkPrice == ""){
      return array("message" => "価格を入力してください");
    }
    $db->insertDrink($drinkName, $drinkPrice);
    return array("message" => "新規作成！");
  }

  public static function changeProduct($changedProduct, $changeProductName, $changeProductPrice){
    $db = new Mapper();
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
         $db->updateProductName($changeProductName, $drinkId);
         $db->updateProductPrice($changeProductPrice, $drinkId);
         $db->commit();
         return array("message" => "名称と価格を変更しました");
         break;
       }elseif(!$changeProductName && $changeProductPrice){
         $db->updateProductPrice($changeProductPrice, $drinkId);
         return array("message" => "価格を変更しました");
         break;
       }elseif($changeProductName && !$changeProductPrice){
         $db->updateProductName($changeProductName, $drinkId);
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
