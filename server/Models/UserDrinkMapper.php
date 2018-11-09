<?php
require_once("MapperBase.php");

class UserDrinkMapper extends MapperBase{

  public function selectAll(){
    return parent::select("user_drink");
  }

  public function selectFromUserId($userid){
    return parent::select("user_drink where user_id = :user_id", array(":user_id" => $userid));
  }

  public function updateDrinks($table, $change, $data = array(), $user_id, $drink_id) {
    try {
      $stmt = $this->db->prepare("update " . $table . " set " . $change . " where user_id = " . $user_id . " and drink_id = " . $drink_id);
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

  public function updateUserDrinkCount($user_id, $drink_id, $drink_count){
    return parent::updateDrinks("user_drink", "drink_count = :drink_count", array(":drink_count"=>$drink_count), $user_id, $drink_id);
  }

  public function insertUserDrinkCount($user_id, $drink_id, $drink_count = 1){
    return parent::insert("user_drink (user_id, drink_id, drink_count) values (:user_id, :drink_id, :drink_count)",
                          array(':user_id' => $user_id,
                                ':drink_id' => $drink_id,
                                ':drink_count' => $drink_count
                                )
                          );
  }


}

// $db = new UserDrinkMapper();
// $userinfo = $db->insertUserDrinkCount(2, 2);
//
// var_dump($userinfo);
