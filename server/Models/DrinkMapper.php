<?php
require_once("MapperBase.php");

class DrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select("drink");
  }

  public function selectFromId($id){
    return parent::select("drink where id = :id", array(":id" => $id));
  }

  public function updateName($id, $name){
    return parent::update("drink", "name = :name", array(":name"=>$name), $id);
  }

  public function updatePrice($id, $price){
    return parent::update("drink", "price = :price", array(":price"=>$price), $id);
  }

  public function insertDrink($name, $price){
    return parent::insert("drink (name, price) values (:name, :price)",
                          array(':name' => $name,
                                ':price' => $price
                                )
                          );
  }
}
