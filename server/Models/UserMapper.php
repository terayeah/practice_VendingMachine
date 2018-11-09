<?php
require_once("MapperBase.php");

class UserMapper extends MapperBase{

  public function selectAll(){
    parent::select("users");
  }

  public function selectFromId($id){
    return parent::select("users where id = :id", array(":id" => $id));
  }

  public function selectFromName($name){
    return parent::select("users where name = :name", array(":name" => $name));
  }

  public function updateCash($id, $cash){
    return parent::update("users", "cash = :cash", array(":cash"=>$cash), $id);
  }

  public function updateSuica($id, $suica){
    return parent::update("users", "suica = :suica", array(":suica"=>$suica), $id);
  }

  public function insertUser($name, $salt, $encrypted_password){
    return parent::insert("users (name, cash, suica, salt, encrypted_password) values (:name, 5000, 2000, :salt, :encrypted_password)",
                          array(':name' => $name,
                                ':salt' => $salt,
                                ':encrypted_password' => $encrypted_password
                                )
                          );
  }
}


// $db = new UserMapper();
// $userinfo = $db->selectFromName("terami");
//
// var_dump($userinfo);



 ?>
