<?php

function displayTransitionButton($where, $value){
  $html .= "<form action='" . $where . ".php' method='POST'>";
  $html .= "<input type='submit' value='" . $value . "'/>";
  $html .= "</form>";
  $html .= "<br>";
  return $html;
}

function displayTopButton(){
  echo "<form action='top.php' method='POST'>";
  echo "<input type='submit' value='自販機選択画面へ'/>";
  echo "</form>";
  echo "<br>";
}

function displayDrinkSettingButton(){
  echo "<form action='drinkSetting.php' method='POST'>";
  echo "<input type='submit' value='飲み物を追加する'/>";
  echo "</form>";
  echo "<br>";
}

function displayVmSettingButton(){
  echo "<form action='vmSetting.php' method='POST'>";
  echo "<input type='submit' value='戻る'/>";
  echo "</form>";
  echo "<br>";
}

function displayBuckButton(){
  echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">戻る</a>';
}

function displayHeader($vm){
  $html .= '<ul>';
  $html .= "<li>自販機売上額 : ¥" . $vm->getTotal() . " </li>";
  $html .= "<li>現在の入金額 : ¥" . $vm->getCharge() . "</li>";
  $html .= "<li>在庫 ";
  foreach ($vm->getStock() as $drinkName => $stock) {
    $html .= $drinkName . " : " . $stock . "本　";
  }
  $html .= '</li></ul><br>';
  return $html;
}

function displayFooter($user, $drinkInfo){
  $html .= '<ul>';
  $html .= "<li>ユーザー名 : " . $user->getName() . " さん</li>";
  $html .= "<li>現在の所持金 : ¥" . $user->getCash() . "</li>";
  $html .= "<li>現在のチャージ額 : ¥" . $user->getSuica() . "</li>";
  $html .= "<li>持ってる飲み物 : ";
  foreach ($user->getDrinkArray() as $drinkId => $howMany) {
    foreach ($drinkInfo as $drink_record) {
      if($drink_record['id'] == $drinkId){
        $drinkName = $drink_record['name'];
        $html .= $drinkName . " : " . $howMany . " 本 ";
      }
    }
  }
  $html .= "</li></ul><br>";
  return $html;
}

function displayDrinkButton($vm){
  $drinkArray = $vm->getDrinks();
  foreach ($drinkArray as $drinkId => $drink) {
    $name = $drink->getName();
    $price = $drink->getPrice();
    if($vm->checkStock($name)){
      $html .= "<button class='selectedDrink' value='" . $drinkId . "'>" . $name . " ¥ " . $price . "</button>";
    }else{
      $html .= "<button class='selectedDrink' value='" . $drinkId . "'>" . $name . " 売り切れ </button>";
    }
  }
  $html .= "<br/>";
  return $html;
}

function displalyResetButton(){
  echo "<form action='top.php' method='POST' onsubmit='return checkSubmit()''>";
  echo "<input type='submit' name='reset' value='リセット'></form>";
  echo "<script type='text/javascript'>";
  echo "function checkSubmit() {
    return confirm('リセットしてよろしいですか？');
  }";
  echo "</script>";
}

function displayCashSlot($vm){
  if ($vm->getType() == VendingMachine::$vm_type_Cash || $vm->getType() == VendingMachine::$vm_type_Both){
    $html .= "<input type='text' id='howMuchCash' placeholder='金額を記入'>";
    $html .= "<button id='putCash'>入金</button>";
    $html .= "<button id='backChange'>お釣り</button>";
    $html .= "<br>";
    return $html;
  }
}

function displaySuicaChargeMachine($vm){
  if ($vm->getType() == VendingMachine::$vm_type_Suica || $vm->getType() == VendingMachine::$vm_type_Both){
    $html .= "<input type='text' id='howMuchSuica' placeholder='Suicaへのチャージ額を記入'>";
    $html .= "<button id='charge'>チャージ</button>";
    $html .= "<br>";
    return $html;
  }
}

function displayBuyButton($vm){
  if ($vm->getType() == VendingMachine::$vm_type_Suica || $vm->getType() == VendingMachine::$vm_type_Both){
    $html .= "<button id='buySuica'>購入</button>";
    $html .= "<br>";
    return $html;
  }
}

function displayAddVendingMachineButton(){
  $html .= "<h4>追加フォーム</h4>";
  $html .= "<select name='vmType'>";
  $html .= "<option value='cash'>現金会計のみ</option>";
  $html .= "<option value='suica'>Suica会計のみ</option>";
  $html .= "<option value='both'>現金Suica両方会計</option>";
  $html .= "<input type='text' id='vmName' placeholder='自販機名'>";
  $html .= "<button id='add_vm'>追加</button>";
  $html .= "<br>";
  return $html;
}

function displayAddDrinkForm($drinkTableArray){
  $html = "<h4>追加フォーム</h4>";
  $html .= "<select name='addedExistingDrink'>";
    foreach ($drinkTableArray as $drinkId => $drink) {
      $html .= "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
    }
  $html .= "<input type='text' id='addDrinkCount' placeholder='個数'>";
  $html .= "</select>";
  $html .= "<button id='addExistingDrink'>追加</button>";
  $html .= "<br>";
  return $html;
}

function displayChangeDrinkForm($drinkArray){
  if(!empty($drinkArray)){
    $html = "<h4>変更フォーム</h4>";
    $html .= "<select name='changedDrink'>";
      foreach ($drinkArray as $drinkId => $drink) {
        $html .= "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
      }
    $html .= "<input type='number' id='changeDrinkStock' placeholder='変更個数'>";
    $html .= "</select>";
    $html .= "<button id='changeDrink'>変更</button>";
    $html .= "<br>";
    return $html;
  }
}

function displayDeleteDrinkForm($drinkArray){
  if(!empty($drinkArray)){
    $html = "<h4>削除フォーム</h4>";
    $html .= "<select name='deletedDrink'>";
      foreach ($drinkArray as $drinkId => $drink) {
        $html .= "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
      }
    $html .= "</select>";
    $html .= "<button id='deleteDrink'>削除</button>";
    $html .= "<br>";
    return $html;
  }
}

function displayMakeProductForm(){
  $html = "<h4>新規商品の開発</h4>";
  $html .= "<input type='text' id='drinkName' placeholder='商品名'>";
  $html .= "<input type='number' id='drinkPrice' placeholder='価格'>";
  $html .= "<button id='addDrink'>追加</button>";
  $html .= "<br>";
  return $html;
}

function displayChangeProductForm($drinkTableArray){
  if(!empty($drinkTableArray)){
    $html = "<h4>既存商品の改革</h4>";
    $html .= "<select name='changedProduct'>";
    foreach ($drinkTableArray as $drinkId => $drink) {
      $html .= "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
    }
    $html .= "<input type='text' id='changeProductName' placeholder='変更名称'>";
    $html .= "<input type='number' id='changeProductPrice' placeholder='変更価格'>";
    $html .= "<button id='changeProduct'>変更</button>";
    $html .= "</select>";
    $html .= "<br>";
    return $html;
  }
}

function createSalt($length = 8){
  return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

 ?>
