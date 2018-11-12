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

function SqlBuySuicaVm($vmdb, $vmdrinkdb, $userdrinkdb, $userdb, $vm, $drink_id, $drink, $user){
  $vmdb->updateSuica($vm->getId(), $vm->getSuica());
  $vmdrinkdb->updateVendingMachineDrinkCount($vm->getId(), $drink_id, $vm->getStock($drink->getName()));
  $userdb->updateSuica($user->getId(), $user->getSuica());
  if($user->getOwnDrinkCount($drink_id) == 1){
    $userdrinkdb->insertUserDrinkCount($user->getId(), $drink_id);
  }else{
    $userdrinkdb->updateUserDrinkCount($user->getId(), $drink_id, $user->getOwnDrinkCount($drink_id));
  }
}
