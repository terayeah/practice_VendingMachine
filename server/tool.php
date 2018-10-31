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
  $html .= "<form action='vmView.php' method='POST'>";
  $drinkArray = $vm->getDrinks();
  foreach ($drinkArray as $drinkId => $drink) {
    $name = $drink->getName();
    $price = $drink->getPrice();
    if($vm->checkStock($name)){
      $html .= "<input type='submit' name='" . $drinkId . "' value='" . $name . " ¥ " . $price . "'>";
    }else{
      $html .= "<input type='submit' name='" . $drinkId . "' value='" . $name . "売り切れ'>";
    }
  }
  $html .= "</form><br/>";
  return $html;
}

function displayVendingMachineButton($vmArray, $cashButton, $suicaButton, $bothButton){
  foreach($vmArray as $vm){
    if ($vm['type'] == VendingMachine::$vm_type_Cash){
      $cashButton .= "<button type='submit' name='" . PostUtil::$selectedVm . "' value='" . $vm['id'] . "'>" . $vm['name'] . "</button></br>";
    }
    if ($vm['type'] == VendingMachine::$vm_type_Suica){
      $suicaButton .= "<button type='submit' name='" . PostUtil::$selectedVm . "' value='" . $vm['id'] . "'>" . $vm['name'] . "</button></br>";
    }
    if ($vm['type'] == VendingMachine::$vm_type_Both){
      $bothButton .= "<button type='submit' name='" . PostUtil::$selectedVm . "' value='" . $vm['id'] . "'>" . $vm['name'] . "</button></br>";
    }
  }
  echo "<h3>現金会計のみの自販機</h3>";
  echo "<form action='vmView.php' method='POST'>";
  echo $cashButton;
  echo "</form>";
  echo "<h3>Suica会計のみの自販機</h3>";
  echo "<form action='vmView.php' method='POST'>";
  echo $suicaButton;
  echo "</form>";
  echo "<h3>現金とSuica両方会計の自販機</h3>";
  echo "<form action='vmView.php' method='POST'>";
  echo $bothButton;
  echo "</form>";
  echo "<br>";
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
    $html .= "<form action='vmView.php' method='POST'>";
    $html .= "<input type='text' name='howMuchCash' placeholder='金額を記入'>";
    $html .= "<input type='submit' name='putCash' value='入金'>";
    $html .= "<input type='submit' name='backChange' value='お釣り'>";
    $html .= "</form>";
    $html .= "<br>";
    return $html;
  }
}

function displaySuicaChargeMachine($vm){
  if ($vm->getType() == VendingMachine::$vm_type_Suica || $vm->getType() == VendingMachine::$vm_type_Both){
    $html .= "<form action='vmView.php' method='POST'>";
    $html .= "<input type='text' name='howMuchSuica' placeholder='Suicaへのチャージ額を記入'>";
    $html .= "<input type='submit' name='charge' value='チャージ'>";
    $html .= "</form>";
    $html .= "<br>";
    return $html;
  }
}

function displayBuyButton($vm){
  if ($vm->getType() == VendingMachine::$vm_type_Suica || $vm->getType() == VendingMachine::$vm_type_Both){
    echo "<form action='vmView.php' method='POST'>
    <input type='submit' name='buySuica' value='購入'>
    </form>
    <br>";
  }
}

function displayAddVendingMachineButton(){
  echo "<form action='vmSetting.php' method='POST'>
    <h4>追加フォーム</h4>
      <select name='vmType'>
        <option value='cash'>現金会計のみ</option>
        <option value='suica'>Suica会計のみ</option>
        <option value='both'>現金Suica両方会計</option>
      <input type='text' name='vmName' placeholder='自販機名'>
      <input type='submit' name='addVm' value='追加'>
    </form>";
}

function displayAddDrinkForm($drinkTableArray){
  echo "<form action='drinkSetting.php' method='POST'>
    <h3>追加フォーム</h3>
      <select name='addedExistingDrink'>";
      foreach ($drinkTableArray as $drinkId => $drink) {
        echo "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
      }
      echo "<input type='number' name='addDrinkCount' placeholder='個数'>
      <input type='submit' name='addExistingDrink' value='追加'>
      </select>
  </form>";
}

function displayChangeDrinkForm($drinkArray){
  if(!empty($drinkArray)){
    echo "<form action='drinkSetting.php' method='POST'>
    <h3>変更フォーム</h3>
      <select name='changedDrink'>";
      foreach ($drinkArray as $drinkId => $drink) {
        echo "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
      }
      echo "<input type='number' name='changeDrinkStock' placeholder='変更個数'>
      <input type='submit' name='changeDrink' value='変更'>
    </select>
    </form>";
  }
}

function displayDeleteDrinkForm($drinkArray){
  if(!empty($drinkArray)){
    echo "<form action='drinkSetting.php' method='POST' onsubmit='return checkSubmit()'>
    <h3>削除フォーム</h3>
      <select name='deletedDrink'>";
      foreach ($drinkArray as $drinkId => $drink) {
        echo "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
      }
      echo "<input type='submit' name='deleteDrink' value='削除'>
    </select>
    </form>
    <br>
    <script type='text/javascript'>
     function checkSubmit() {
      return confirm('削除してもよろしいですか？');
     }
    </script>";
  }
}

function displayMakeProductForm(){
  echo "<form action='drinkSetting.php' method='POST'>
    <h3>新規商品の開発</h3>
      <input type='text' name='drinkName' placeholder='商品名'>
      <input type='number' name='drinkPrice' placeholder='価格'>
      <input type='submit' name='addDrink' value='追加'>
  </form>";
}

function displayChangeProductForm($drinkTableArray){
  if(!empty($drinkTableArray)){
    echo "<form action='drinkSetting.php' method='POST'>
    <h3>変更フォーム</h3>
      <select name='changedProduct'>";
      foreach ($drinkTableArray as $drinkId => $drink) {
        echo "<option value=" . $drinkId . ">" . $drink->getName() . "¥" . $drink->getPrice() . "</option>";
      }
      echo "<input type='text' name='changeProductName' placeholder='変更名称'>
      <input type='number' name='changeProductPrice' placeholder='変更価格'>
      <input type='submit' name='changeProduct' value='変更'>
    </select>
    </form>";
  }
}

function createSalt($length = 8){
  return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
}

 ?>
