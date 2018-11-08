<?php
class Drink{
  private $name;
  private $price;

  public function __construct($name, $price){
    $this->name = $name;
    $this->price = $price;
  }

  public function getName(){
    return $this->name;
  }

  public function getId($name, $db){
    $stmt = $db->query("select * from drink where name = " . $name);
    $userRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $id = $userRecord[0]['id'];
    return $id;
  }

  public static function getNameFromId($id, $db){
    $stmt = $db->query("select * from drink where id = " . $id);
    $drinkRecord = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $name = $drinkRecord[0]['name'];
    return $name;
  }

  public function getPrice(){
    return $this->price;
  }

  public function getJsonArray(){
    return array("name"=>$this->getName(),"price"=>$this->getPrice());
  }
}

 ?>
