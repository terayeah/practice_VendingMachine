<?php
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
    return array("name"=>$this->getName(),
                 "cash"=>$this->getCash(),
                 "suica"=>$this->getSuica());
  }

  public function getName(){
    return $this->name;
  }

  public function getId(){
    return $this->id;
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

  public function addDrink($drinkId){
    $this->drinkArray[$drinkId] = $this->drinkArray[$drinkId] + 1;
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

  public function getOwnDrinkCount($drinkId){
    $count = $this->drinkArray[$drinkId];
    return $count;
  }

  public function checkWallet($howMuch){
    if($this->getCash() >= $howMuch){
      return true;
    }else{
      return false;
    }
  }

  public function chargeSuica($howMuch, $db){
    if($this->checkWallet($howMuch)){
      $this->decCash($howMuch);
      $this->addSuica($howMuch);
      $db->chargeSuica($this->cash, $this->suica, $this->name);
    }
  }
}
 ?>
