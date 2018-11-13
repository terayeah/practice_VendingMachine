<?php
require_once("MapperBase.php");

class UserMapper extends MapperBase{

  public function selectAll(){
    return parent::select("users")
    ->content()
    ->fetchAll();
  }

  public function selectFromId($id){
    return parent::select("users")
    ->where("id = :id")
    ->content(array(":id" => $id))
    ->fetchAll();
  }

  public function selectFromName($name){
    return parent::select("users")
    ->where("name = :name")
    ->content(array(":name" => $name))
    ->fetchAll();
  }

  public function updateCash($id, $cash){
    parent::update("users")
    ->setCol("cash = :cash")
    ->where("id = :id")
    ->content(array(":cash" => $cash, ":id" => $id));
  }

  public function updateSuica($id, $suica){
    parent::update("users")
    ->setCol("suica = :suica")
    ->where("id = :id")
    ->content(array(":suica" => $suica, ":id" => $id));
  }


  public function insertUser($name, $salt, $encrypted_password){
    parent::insert("users (name, cash, suica, salt, encrypted_password)")
    ->values("(:name, 5000, 2000, :salt, :encrypted_password)")
    ->content(array(':name' => $name,
          ':salt' => $salt,
          ':encrypted_password' => $encrypted_password
        ));
  }
}



 ?>
