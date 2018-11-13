<?php
require_once("MapperBase.php");

class DrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select()
    ->from("drink")
    ->execute()
    ->fetchAll();
  }

  public function selectFromId($id){
    return parent::select()
    ->from("drink")
    ->where(array("id = :id"))
    ->setData(array(":id" => $id))
    ->execute()
    ->fetchAll();
  }

  public function updateName($name, $id){
    parent::update("drink")
    ->setCol("name = :name")
    ->where(array("id = :id"))
    ->setData(array(":name" => $name, ":id" => $id))
    ->execute();
  }

  public function updatePrice($price, $id){
    parent::update("drink")
    ->setCol("price = :price")
    ->where(array("id = :id"))
    ->setData(array(":price" => $price, ":id" => $id))
    ->execute();
  }

  public function insertDrink($name, $price){
    parent::insert("drink (name, price)")
    ->values("(:name, :price)")
    ->setData(array(':name' => $name, ':price' => $price))
    ->execute();
  }
}
