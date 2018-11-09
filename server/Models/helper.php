<?php

 function putCash($charge, $id, $userCash, $userName){
  $this->db->exec("update vending_machine set charge = " . $charge . " where id = '" . $id . "'");
  $this->db->exec("update users set cash = " . $userCash . " where name = '" . $userName . "'");
}

 function backChange($usercash, $username, $vmid){
  $this->db->exec("update users set cash = " . $usercash . " where name = '" . $username . "'");
  $this->db->exec("update vending_machine set charge = 0 where id = '" . $vmid . "'");
}

 function chargeSuica($usercash, $usersuica, $username){
  $this->db->exec("update users set cash = " . $usercash . " where name = '" . $username . "'");
  $this->db->exec("update users set suica = " . $usersuica . " where name = '" . $username . "'");
}

 function buyCashVm($vmcharge, $vmcash, $vmid, $vmstockarraycount, $user, $drinkId){
  $this->db->exec("update vending_machine set charge = " . $vmcharge . " where id = '" . $vmid . "'");
  $this->db->exec("update vending_machine set cash = " . $vmcash . " where id = '" . $vmid . "'");
  $this->db->exec("update vending_machine_drink set drink_count = " . $vmstockarraycount . " where vending_machine_id = " . $vmid . " and drink_id = " . $drinkId);
  if($user->getOwnDrinkCount($drinkId) == 1){
      $this->db->exec("insert into user_drink (user_id, drink_id, drink_count) values (" . $user->getId() . ", " . $drinkId . ", 1)");
    }else{
      $this->db->exec("update user_drink set drink_count = " . $user->getOwnDrinkCount($drinkId) . " where user_id = " . $user->getId() . " and drink_id = " . $drinkId);
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
