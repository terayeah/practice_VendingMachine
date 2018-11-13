<?php
require_once("MapperBase.php");

class UserDrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select()
    ->from("user_drink")
    ->execute()
    ->fetchAll();
  }

  public function selectFromUserId($userid){
    return parent::select()
    ->from("user_drink")
    ->where(array("user_id = :user_id"))
    ->setData(array(":user_id" => $userid))
    ->execute()
    ->fetchAll();
  }

  public function updateUserDrinkCount($user_id, $drink_id, $drink_count){
    parent::update("user_drink")
    ->setCol("drink_count = :drink_count")
    ->where(array("user_id = :user_id", "drink_id = :drink_id"))
    ->setData(array(":drink_count" => $drink_count,
    ":user_id" => $user_id,
    ":drink_id" => $drink_id))
    ->execute();
  }

  public function insertUserDrinkCount($user_id, $drink_id, $drink_count = 1){
    parent::insert("user_drink (user_id, drink_id, drink_count)")
    ->values("(:user_id, :drink_id, :drink_count)")
    ->setData(array(':user_id' => $user_id,
          ':drink_id' => $drink_id,
          ':drink_count' => $drink_count
        ))
    ->execute();
  }


}
