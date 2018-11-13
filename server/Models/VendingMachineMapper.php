<?php
require_once("MapperBase.php");

class VendingMachineMapper extends MapperBase{

  public function selectAll(){
    return parent::select("vending_machine")
    ->content()
    ->fetchAll();
  }

  public function selectFromId($id){
    return parent::select("vending_machine")
    ->where("id = :id")
    ->content(array(":id" => $id))
    ->fetchAll();
  }

  public function updateCash($id, $cash){
    parent::update("vending_machine")
    ->setCol("cash = :cash")
    ->where("id = :id")
    ->content(array(":cash" => $cash, ":id" => $id));
  }

  public function updateSuica($id, $suica){
    parent::update("vending_machine")
    ->setCol("suica = :suica")
    ->where("id = :id")
    ->content(array(":suica" => $suica, ":id" => $id));
  }

  public function updateCharge($id, $charge){
    parent::update("vending_machine")
    ->setCol("charge = :charge")
    ->where("id = :id")
    ->content(array(":charge" => $charge, ":id" => $id));
  }

  public function insertVendingMachine($name, $type){
    parent::insert("vending_machine (name, type)")
    ->values("(:name, :type)")
    ->content(array(':name' => $name, ':type' => $type));
  }
}
