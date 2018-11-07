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

  public static function putCash($userEncrypt, $howMuchCash){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    $vm->putCash($user, $howMuchCash, $db);
  }

  public static function backChange($userEncrypt){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    $vm->backChange($user, $db);
  }

  public static function charge($userEncrypt, $howMuchSuica){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    $user->chargeSuica($howMuchSuica, $db);
  }

  public static function selectedDrink($userEncrypt, $selectedDrink){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);

    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];

    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($db, $drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();

    $message;

    foreach ($drinkArray as $drinkId => $drink){
      if($selectedDrink == $drinkId){
        switch ($vm->getType()){
          case VendingMachine::$vm_type_Cash:
            $result = $vm->buyCashVm($user, $drink, $drinkId, $db);
            break;
          case VendingMachine::$vm_type_Suica:
            $vm->choiceSuicaDrink($drink, $drinkId);
            break;
          case VendingMachine::$vm_type_Both:
            if($vm->getCharge() > 0){
              $vm->buyCashVm($user, $drink, $drinkId, $db);
            }elseif($vm->getCharge() == 0){
              $vm->choiceSuicaDrink($drink, $drinkId);
            }
            break;
        }
        break;
      }
    }
  }

  public static function buySuica($userEncrypt){
    $db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($db, $drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();
    $vm->buySuicaVm($user, $drinkArray, $db);
  }







}
