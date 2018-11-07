<?php
require_once("define.php");

class UserController{

  static function isQuerrySuccess($result){
  if ($result === false) {
    return false;
  }
  if(count($result) != 1){
    return false;
  }
  return true;
  }

  public static function signup($newUsername, $newPassword){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $stmt = $db->query("select * from users");
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    function createSalt($length = 8){
      return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
    }

    $isUpdated = false;
    if($newUsername !=  "" && $newPassword !=  ""){
      foreach($userInfo as $user){
        if($user['name'] == $newUsername){
          $isUpdated = true;
        }
      }
      if(!$isUpdated){
        $salt = createSalt();
        $encrypted_password = crypt($newPassword, $salt);
        $db->exec("insert into users (name, cash, suica, salt, encrypted_password) values ('" . $newUsername . "', 5000, 2000, '" . $salt . "', '" . $encrypted_password . "')");
      }
    }

    return array(
        "isUpdated" => $isUpdated
    );
  }

  public static function login(){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  }
}
