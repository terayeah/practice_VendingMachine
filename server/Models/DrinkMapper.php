<?php
require_once("MapperBase.php");

class DrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select("drink")
    ->content()
    ->fetchAll();
  }

  public function selectFromId($id){
    return parent::select("drink")
    ->where("id = :id")
    ->content(array(":id" => $id))
    ->fetchAll();
  }

  public function updateName($name, $id){
    parent::update("drink")
    ->setCol("name = :name")
    ->where("id = :id")
    ->content(array(":name" => $name, ":id" => $id));
  }

  public function updatePrice($price, $id){
    parent::update("drink")
    ->setCol("price = :price")
    ->where("id = :id")
    ->content(array(":price" => $price, ":id" => $id));
  }

  public function insertDrink($name, $price){
    parent::insert("drink (name, price)")
    ->values("(:name, :price)")
    ->content(array(':name' => $name, ':price' => $price));
  }
}
