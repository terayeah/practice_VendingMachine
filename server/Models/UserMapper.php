<?php
require_once("MapperBase.php");

class UserMapper extends MapperBase{

  public function selectAll(){
    return parent::select()
    ->from("users")
    ->execute()
    ->fetchAll();
  }

  public function selectFromId($id){
    return parent::select()
    ->from("users")
    ->where(array("id = :id"))
    ->setData(array(":id" => $id))
    ->execute()
    ->fetchAll();
  }

  public function selectFromName($name){
    return parent::select()
    ->from("users")
    ->where(array("name = :name"))
    ->setData(array(":name" => $name))
    ->execute()
    ->fetchAll();
  }

  public function updateCash($id, $cash){
    parent::update("users")
    ->setCol("cash = :cash")
    ->where(array("id = :id"))
    ->setData(array(":cash" => $cash, ":id" => $id))
    ->execute();
  }

  public function updateSuica($id, $suica){
    parent::update("users")
    ->setCol("suica = :suica")
    ->where(array("id = :id"))
    ->setData(array(":suica" => $suica, ":id" => $id))
    ->execute();
  }


  public function insertUser($name, $salt, $encrypted_password){
    parent::insert("users (name, cash, suica, salt, encrypted_password)")
    ->values("(:name, 5000, 2000, :salt, :encrypted_password)")
    ->setData(array(':name' => $name,
          ':salt' => $salt,
          ':encrypted_password' => $encrypted_password
        ))
        ->execute();
  }
}



 ?>
