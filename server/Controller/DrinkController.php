<?php

class DrinkController{

  public static function makeProduct($drinkName, $drinkPrice){
    $drinkdb = new DrinkMapper();
    if(empty($drinkName)){
      return array("message" => "商品名を入力してください");
    }
    if(empty($drinkPrice)){
      return array("message" => "価格を入力してください");
    }
    $drinkdb->insertDrink($drinkName, $drinkPrice);
    return array("message" => "新規作成！");
  }

  public static function changeProduct($changedProduct, $changeProductName, $changeProductPrice){
    $drinkdb = new DrinkMapper();
    //$drinkTableArrayの作成
    $drinkTableArray = array();
    $drinkInfo = $_SESSION['drinkInfo'];
    foreach ($drinkInfo as $value) {
      $drinkTableArray[$value['id']] = new Drink($value['name'], $value['price']);
    }
    foreach ($drinkTableArray as $drinkId => $drink) {
     if($drinkId == $changedProduct){
       if ($changeProductName && $changeProductPrice){
         $drinkdb->beginTransaction();
         $drinkdb->updateName($changeProductName, $drinkId);
         $drinkdb->updatePrice($changeProductPrice, $drinkId);
         $drinkdb->commit();
         return array("message" => "名称と価格を変更しました");
         break;
       }elseif(!$changeProductName && $changeProductPrice){
         $drinkdb->updatePrice($changeProductPrice, $drinkId);
         return array("message" => "価格を変更しました");
         break;
       }elseif($changeProductName && !$changeProductPrice){
         $drinkdb->updateName($changeProductName, $drinkId);
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
