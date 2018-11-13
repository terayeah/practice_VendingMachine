<?php
require_once("MapperBase.php");

class UserDrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select("user_drink")
    ->content()
    ->fetchAll();
  }

  public function selectFromUserId($userid){
    return parent::select("user_drink")
    ->where("user_id = :user_id")
    ->content(array(":user_id" => $userid))
    ->fetchAll();
  }

  public function updateUserDrinkCount($user_id, $drink_id, $drink_count){
    parent::update("user_drink")
    ->setCol("drink_count = :drink_count")
    ->where("user_id = :user_id")
    ->and("drink_id = :drink_id")
    ->content(array(":drink_count" => $drink_count,
    ":user_id" => $user_id,
    ":drink_id" => $drink_id));
  }

  public function insertUserDrinkCount($user_id, $drink_id, $drink_count = 1){
    parent::insert("user_drink (user_id, drink_id, drink_count)")
    ->values("(:user_id, :drink_id, :drink_count)")
    ->content(array(':user_id' => $user_id,
          ':drink_id' => $drink_id,
          ':drink_count' => $drink_count
        ));
  }


}
