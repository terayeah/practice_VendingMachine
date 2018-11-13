<?php
require_once("MapperBase.php");

class VendingMachineMapper extends MapperBase{

  public function selectAll(){
    return parent::select()
    ->from("vending_machine")
    ->execute()
    ->fetchAll();
  }

  public function selectFromId($id){
    return parent::select()
    ->from("vending_machine")
    ->where(array("id = :id"))
    ->setData(array(":id" => $id))
    ->execute()
    ->fetchAll();
  }

  public function updateCash($id, $cash){
    parent::update("vending_machine")
    ->setCol("cash = :cash")
    ->where(array("id = :id"))
    ->setData(array(":cash" => $cash, ":id" => $id))
    ->execute();
  }

  public function updateSuica($id, $suica){
    parent::update("vending_machine")
    ->setCol("suica = :suica")
    ->where(array("id = :id"))
    ->setData(array(":suica" => $suica, ":id" => $id))
    ->execute();
  }

  public function updateCharge($id, $charge){
    parent::update("vending_machine")
    ->setCol("charge = :charge")
    ->where(array("id = :id"))
    ->setData(array(":charge" => $charge, ":id" => $id))
    ->execute();
  }

  public function insertVendingMachine($name, $type){
    parent::insert("vending_machine (name, type)")
    ->values("(:name, :type)")
    ->setData(array(':name' => $name, ':type' => $type))
    ->execute();
  }
}
