<?php

function SqlPutCash($vmdb, $userdb, $vmid, $vmcharge, $userid, $usercash){
  $vmdb->updateCharge($vmid, $vmcharge);
  $userdb->updateCash($userid, $usercash);
}

function SqlBackChange($userdb, $vmdb, $userid, $usercash, $vmid, $vmcharge){
  $userdb->updateCash($userid, $usercash);
  $vmdb->updateCharge($vmid, $vmcharge);
}

function SqlChargeSuica($userdb, $userid, $usercash, $usersuica){
  $userdb->updateCash($userid, $usercash);
  $userdb->updateSuica($userid, $usersuica);
}

function SqlBuyCashVm($vmdb, $vmdrinkdb, $userdrinkdb, $vm, $drink_id, $drink, $user){
  $vmdb->updateCharge($vm->getId(), $vm->getCharge());
  $vmdb->updateCash($vm->getId(), $vm->getCash());
  $vmdrinkdb->updateVendingMachineDrinkCount($vm->getId(), $drink_id, $vm->getStock($drink->getName()));
 if($user->getOwnDrinkCount($drink_id) == 1){
   $userdrinkdb->insertUserDrinkCount($user->getId(), $drink_id);
 }else{
   $userdrinkdb->updateUserDrinkCount($user->getId(), $drink_id, $user->getOwnDrinkCount($drink_id));
 }
}

 function buySuicaVm($vmsuica, $vmid, $vmstockarraycount, $user, $drinkId){
  $this->db->exec("update vending_machine set suica = " . $vmsuica . " where id = '" . $vmid . "'");
  $this->db->exec("update vending_machine_drink set drink_count = " . $vmstockarraycount . " where vending_machine_id = " . $vmid . " and drink_id = " . $drinkId);
  $this->db->exec("update users set suica = " . $user->getSuica() . " where name = '" . $user->getName() . "'");
  if($user->getOwnDrinkCount($drinkId) == 1){
      $this->db->exec("insert into user_drink (user_id, drink_id, drink_count) values (" . $user->getId() . ", " . $drinkId . ", 1)");
    }else{
      $this->db->exec("update user_drink set drink_count = " . $user->getOwnDrinkCount($drinkId) . " where user_id = " . $user->getId() . " and drink_id = " . $drinkId);
    }
  }
