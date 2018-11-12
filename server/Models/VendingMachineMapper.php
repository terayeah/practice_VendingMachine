<?php
require_once("MapperBase.php");

class VendingMachineMapper extends MapperBase{

  public function selectAll(){
    return parent::select("vending_machine");
  }

  public function selectFromId($id){
    return parent::select("vending_machine where id = :id", array(":id" => $id));
  }

  public function updateCash($id, $cash){
    return parent::update("vending_machine", "cash = :cash", array(":cash"=>$cash), $id);
  }

  public function updateSuica($id, $suica){
    return parent::update("vending_machine", "suica = :suica", array(":suica"=>$suica), $id);
  }

  public function updateCharge($id, $charge){
    return parent::update("vending_machine", "charge = :charge", array(":charge"=>$charge), $id);
  }

  public function insertVendingMachine($name, $type){
    parent::insert("vending_machine (name, type) values (:name, :type)",
                          array(':name' => $name,
                                ':type' => $type
                                )
                          );
  }
}
