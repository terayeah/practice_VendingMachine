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

  public function getPrice(){
    return $this->price;
  }

  public function getJsonArray(){
    return array("name"=>$this->getName(),"price"=>$this->getPrice());
  }
}

 ?>
