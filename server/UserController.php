<?php
require_once("define.php");

class UserController{

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

  public static function login($username){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $stmt = $db->query("select * from users where name = '" . $username . "'");
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $userId = $userInfo[0]['id'];
    $userEncrypt = $userInfo[0]['encrypted_password'];
    $_SESSION[$userEncrypt] = $userId;

    return array(
        "encrypted_password" => htmlspecialchars($userEncrypt)
    );
  }







}
