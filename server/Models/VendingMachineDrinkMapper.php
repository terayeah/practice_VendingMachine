<?php
require_once("MapperBase.php");

class VendingMachineDrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select("vending_machine_drink")
    ->content()
    ->fetchAll();
  }

  public function selectFromVmId($vending_machine_id){
    return parent::select("vending_machine_drink")
    ->where("vending_machine_id = :vending_machine_id")
    ->content(array(":vending_machine_id" => $vending_machine_id))
    ->fetchAll();
  }

  public function updateVendingMachineDrinkCount($vending_machine_id, $drink_id, $drink_count){
    parent::update("vending_machine_drink")
    ->setCol("drink_count = :drink_count")
    ->where("vending_machine_id = :vending_machine_id")
    ->and("drink_id = :drink_id")
    ->content(array(":drink_count" => $drink_count,
    ":vending_machine_id" => $vending_machine_id,
    ":drink_id" => $drink_id));
  }

  public function insertVendingMachineDrinkCount($vending_machine_id, $drink_id, $drink_count){
    parent::insert("vending_machine_drink (vending_machine_id, drink_id, drink_count)")
    ->values("(:vending_machine_id, :drink_id, :drink_count)")
    ->content(array(':vending_machine_id' => $vending_machine_id,
          ':drink_id' => $drink_id,
          ':drink_count' => $drink_count
        ));
  }

  public function deleteVendignMachineDrinkCount($vending_machine_id, $drink_id){
    parent::delete("vending_machine_drink")
    ->where("vending_machine_id = :vending_machine_id")
    ->and("drink_id = :drink_id")
    ->content(array(":vending_machine_id" => $vending_machine_id,
                  ":drink_id" => $drink_id));
  }


}
