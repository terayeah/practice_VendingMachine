<?php
// require_once("Drink.php");
// require_once("VendingMachine.php");
// require_once("DataBase.php");

class User{
  private $id;
  private $name;
  private $cash;
  private $suica = 0;
  private $drinkArray = array();

  public function __construct($id, $name = "user", $cash = 5000, $suica = 0, $drinkArray = array()){
    $this->id = $id;
    $this->name = $name;
    $this->cash = $cash;
    $this->suica = $suica;
    $this->drinkArray = $drinkArray;
  }


  public function getJsonArray(){
    return array("name"=>$this->getName(), "cash"=>$this->getCash(), "suica"=>$this->getSuica());
  }

  public function getName(){
    return $this->name;
  }

  public function getId(){
    return $this->id;
  }

  public static function getIdFromName($name, $db){
    $stmt = $db->query("select * from users where name = '" . $name . "'");
    $userRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $id = $userRecord[0]['id'];
    return $id;
  }

  public function addCash($change){
    $this->cash += $change;
  }

  public function decCash($pay){
    $this->cash -= $pay;
  }

  public function getCash(){
    return $this->cash;
  }

  public function addSuica($charge){
    $this->suica += $charge;
  }

  public function decSuica($pay){
    $this->suica -= $pay;
  }

  public function getSuica(){
    return $this->suica;
  }

  public function addDrink($drinkId, $userName, $db){
    $this->drinkArray[$drinkId] = $this->drinkArray[$drinkId] + 1;
    $userId = $this->getId();
    if($this->drinkArray[$drinkId] == 1){
      $db->exec("insert into user_drink (user_id, drink_id, drink_count) values (" . $userId . ", " . $drinkId . ", 1)");
    }else{
      $db->exec("update user_drink set drink_count = " . $this->drinkArray[$drinkId] . " where user_id = " . $userId . " and drink_id = " . $drinkId);
    }
  }

  public function setDrinkArray($db, $user_drink){
    foreach ($user_drink as $user_drink_record) {
      $drink_count = $user_drink_record['drink_count'];
      $this->drinkArray[$user_drink_record['drink_id']] = $drink_count;
    }
  }


  public function getDrinkArray(){
    return $this->drinkArray;
  }

  public function checkWallet($howMuch){
    if($this->getCash() >= $howMuch){
      return true;
    }else{
      echo "お金が足りません</br>";
      return false;
    }
  }

  public function chargeSuica($howMuch, $db){
    if($this->checkWallet($howMuch)){
      $this->decCash($howMuch);
      $this->addSuica($howMuch);
      $db->beginTransaction();
      $db->exec("update users set cash = " . $this->cash . " where name = '" . $this->name . "'");
      $db->exec("update users set suica = " . $this->suica . " where name = '" . $this->name . "'");
      $db->commit();
    }
  }
}



 ?>
