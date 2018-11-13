<?php
require_once("MapperBase.php");

class VendingMachineDrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select()
    ->from("vending_machine_drink")
    ->execute()
    ->fetchAll();
  }

  public function selectFromVmId($vending_machine_id){
    return parent::select()
    ->from("vending_machine_drink")
    ->where(array("vending_machine_id = :vending_machine_id"))
    ->setData(array(":vending_machine_id" => $vending_machine_id))
    ->execute()
    ->fetchAll();
  }

  public function updateVendingMachineDrinkCount($vending_machine_id, $drink_id, $drink_count){
    parent::update("vending_machine_drink")
    ->setCol("drink_count = :drink_count")
    ->where(array("vending_machine_id = :vending_machine_id", "drink_id = :drink_id"))
    ->setData(array(":drink_count" => $drink_count,
    ":vending_machine_id" => $vending_machine_id,
    ":drink_id" => $drink_id))
    ->execute();
  }

  public function insertVendingMachineDrinkCount($vending_machine_id, $drink_id, $drink_count){
    parent::insert("vending_machine_drink (vending_machine_id, drink_id, drink_count)")
    ->values("(:vending_machine_id, :drink_id, :drink_count)")
    ->setData(array(':vending_machine_id' => $vending_machine_id,
          ':drink_id' => $drink_id,
          ':drink_count' => $drink_count
        ))
    ->execute();
  }

  public function deleteVendignMachineDrinkCount($vending_machine_id, $drink_id){
    parent::delete("vending_machine_drink")
    ->where(array("vending_machine_id = :vending_machine_id", "drink_id = :drink_id"))
    ->setData(array(":vending_machine_id" => $vending_machine_id,
                  ":drink_id" => $drink_id))
    ->execute();
  }


}
