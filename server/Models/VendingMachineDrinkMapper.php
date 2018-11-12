<?php
require_once("MapperBase.php");

class VendingMachineDrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select("vending_machine_drink");
  }

  public function selectFromVmId($vending_machine_id){
    return parent::select("vending_machine_drink where vending_machine_id = :vending_machine_id", array(":vending_machine_id" => $vending_machine_id));
  }

  public function updateDrinks($table, $change, $data = array(), $vending_machine_id, $drink_id) {
    try {
      $stmt = $this->db->prepare("update " . $table . " set " . $change . " where vending_machine_id = " . $vending_machine_id . " and drink_id = " . $drink_id);
      if ($stmt->execute($data)) {
        return true;
      }
      else {
        return false;
      }
    }
    catch (Exception $e) {
      return false;
    }
  }

  public function updateVendingMachineDrinkCount($vending_machine_id, $drink_id, $drink_count){
    return $this->updateDrinks("vending_machine_drink", "drink_count = :drink_count", array(":drink_count"=>$drink_count), $vending_machine_id, $drink_id);
  }

  public function insertVendingMachineDrinkCount($vending_machine_id, $drink_id, $drink_count = 1){
    return parent::insert("vending_machine_drink (vending_machine_id, drink_id, drink_count) values (:vending_machine_id, :drink_id, :drink_count)",
                          array(':vending_machine_id' => $vending_machine_id,
                                ':drink_id' => $drink_id,
                                ':drink_count' => $drink_count
                                )
                          );
  }

  public function deleteVendignMachineDrinkCount($vending_machine_id, $drink_id) {
    try {
      $stmt = $this->db->exec("delete from vending_machine_drink where vending_machine_id = " . $vending_machine_id . " and drink_id = " . $drink_id);
      return true;
    }
    catch (Exception $e) {
      return false;
    }
  }


}
