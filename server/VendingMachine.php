<?php
Class VendingMachine{
  public static $vm_type_Cash = 'cash';
  public static $vm_type_Suica = 'suica';
  public static $vm_type_Both = 'both';
  private $type;
  private $name;
  private $cash = 0;
  private $suica = 0;
  private $total = 0;
  private $charge = 0;
  private $drinkArray = array();
  private $drinkJsonArray = array();
  private $stockArray = array();
  private $id;

  public function __construct($id, $name, $type, $cash, $suica, $charge = 0){
    $this->id = $id;
    $this->name = $name;
    $this->type = $type;
    $this->cash = $cash;
    $this->suica = $suica;
    $this->charge = $charge;
  }

  public function getJsonArray(){
    return array("id"=>$this->getId(),
                 "name"=>$this->getName(),
                 "type"=>$this->getType(),
                 "total"=>$this->getTotal(),
                 "charge"=>$this->getCharge());
  }

  public function getName(){
    return $this->name;
  }

  public function getType(){
    return $this->type;
  }

  public function getId(){
    return $this->id;
  }

  public function addCash($drinkPrice){
    $this->cash += $drinkPrice;
  }

  public function addSuica($drinkPrice){
    $this->suica += $drinkPrice;
  }

  public function getTotal(){
    $this->total = $this->cash + $this->suica;
    return $this->total;
  }

  public function checkSuica($user, $drink){
    if($user->getSuica() >= $drink->getPrice()){
      return true;
    }else{
      return false;
    }
  }

  public function addCharge($howMuch){
    $this->charge += $howMuch;
  }

  public function decCharge($howmuch){
    $this->charge -= $howmuch;
  }

  public function checkCharge($drink){
    if($this->getCharge() >= $drink->getPrice()){
      return true;
    }else{
      return false;
    }
  }

  public function getCharge(){
    return $this->charge;
  }

  public function setStock($drinkName, $stock){
    $this->stockArray[$drinkName] = $stock;
  }

  public function decStock($drinkId){
    $drinkInfo = $_SESSION['drinkInfo'];
    $drinkName = $drinkInfo[$drinkId - 1]['name'];
    foreach ($this->stockArray as $key => $value){
      if($key == $drinkName){
        $this->stockArray[$drinkName] -= 1;
        break;
      }
    }
  }

  public function unsetDrinkStock($drinkName){
    foreach ($this->stockArray as $key => $value){
      if($key == $drinkName){
        unset($this->stockArray[$key]);
        break;
      }
    }
  }

  public function getStock(){
    return $this->stockArray;
  }

  public function checkStock($drinkName){
    foreach ($this->stockArray as $key => $value){
      if($key == $drinkName){
        if($this->stockArray[$key] > 0){
          return true;
        }else{
          return false;
        }
      }
    }
  }

  public function setDrinkArray($db, $drink_in_vending_machine){
    foreach ($drink_in_vending_machine as $drink){
      $drink_id = $drink['drink_id'];
      $drink_stock = $drink['drink_count'];
      $drinkInfo = $_SESSION['drinkInfo'];
      foreach ($drinkInfo as $drink_record) {
        if($drink_record['id'] == $drink['drink_id']){
          $drink_name = $drink_record['name'];
          $drink_price = $drink_record['price'];
          $drink = new Drink($drink_name, $drink_price);
          $drinkArray[$drink_id] = $drink;
          $drinkJsonArray[$drink_id] = $drink->getJsonArray();
          $this->setStock($drink_name, $drink_stock);
          break;
        }
      }
    }
    $this->setDrinks($drinkArray);
    $this->setDrinksJsonArray($drinkJsonArray);
  }

  public function setDrinks($drinkArray){
    $this->drinkArray = $drinkArray;
  }

  public function setDrinksJsonArray($drinkJsonArray){
    $this->drinkJsonArray = $drinkJsonArray;
  }

  public function getDrinks(){
    return $this->drinkArray;
  }

  public function getDrinksJsonArray(){
    return $this->drinkJsonArray;
  }

    public static function getVendingMachineFromName($vmArray, $name, $type){
    foreach ($vmArray as $vms) {
      if($vms['name'] == $name && $vms['type'] == $type){
       $vm = new VendingMachine($vms['name'], $vms['type']);
      }
    }
    return null;
  }

  public function putCash($user, $howMuch, $db){
    if($user->checkWallet($howMuch)){
      $this->addCharge($howMuch);
      $user->decCash($howMuch);
      $db->beginTransaction();
      $db->exec("update vending_machine set charge = " . $this->charge . " where id = '" . $this->id . "'");
      $db->exec("update users set cash = " . $user->getCash() . " where name = '" . $user->getName() . "'");
      $db->commit();
      return array("error" => null);
    }else{
      return array("error" => "お金が足りません");
    }
  }

  public function buyCashVm($user, $drink, $drinkId, $db){
    if($user->checkWallet($drink->getPrice())){
      if($this->checkStock($drink->getName())){
        if($this->checkCharge($drink)){
          $this->decCharge($drink->getPrice());
          $this->addCash($drink->getPrice());
          $this->decStock($drinkId);
          $db->beginTransaction();
          $db->exec("update vending_machine set charge = " . $this->charge . " where id = '" . $this->id . "'");
          $db->exec("update vending_machine set cash = " . $this->cash . " where id = '" . $this->id . "'");
          $db->exec("update vending_machine_drink set drink_count = " . $this->stockArray[$drink->getName()] . " where vending_machine_id = " . $this->id . " and drink_id = " . $drinkId);
          $user->addDrink($drinkId, $user->getName(), $db);
          $db->commit();
          return array("error" => null,
                        "message" => "ありがとうございます");
        }else{
          return array("error" => "入金してください");
        }
      }else{
        return array("error" => "在庫がありません");
      }
    }else{
      return array("error" => "お金が足りません");
    }
  }

  public function choiceSuicaDrink($drink, $drinkId){
    if($this->checkStock($drink->getName())){
      $_SESSION["choice"] = $drinkId;
      return array("error" => null,
                   "message" => "を選択中",
                   "drinkname" => $drink->getName()
                  );
    }else{
      return array("error" => "在庫がありません");
    }
  }

  public function buySuicaVm($user, $drinkArray, $db){
    foreach ($drinkArray as $drinkId => $drink) {
      if($_SESSION["choice"] == $drinkId){
        if($this->checkSuica($user, $drink)){
          $this->addSuica($drink->getPrice());
          $this->decStock($drinkId);
          $user->decSuica($drink->getPrice());
          $db->beginTransaction();
          $db->exec("update vending_machine set suica = " . $this->suica . " where id = '" . $this->id . "'");
          $db->exec("update vending_machine_drink set drink_count = " . $this->stockArray[$drink->getName()] . " where vending_machine_id = " . $this->id . " and drink_id = " . $drinkId);
          $db->exec("update users set suica = " . $user->getSuica() . " where name = '" . $user->getName() . "'");
          $user->addDrink($drinkId, $user->getName(), $db);
          $db->commit();
          $_SESSION["choice"] = "";
          return array("error" => null,
                        "message" => "ありがとうございます");
          break;
        }else{
          return array("error" => "suica残高が足りません");
        }
      }
    }
  }

  public function backChange($user, $db){
    $user->addCash($this->charge);
    $cash = $user->getCash();
    $name = $user->getName();
    $db->beginTransaction();
    $db->exec("update users set cash = " . $cash . " where name = '" . $name . "'");
    $this->charge = 0;
    $db->exec("update vending_machine set charge = 0 where id = '" . $this->id . "'");
    $db->commit();
  }

}

 ?>
