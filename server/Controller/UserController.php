<?php

class UserController{

  public static function signup($newUsername, $newPassword){
    $userdb = new UserMapper();
    $userInfo = $userdb->selectAll();
    function createSalt($length = 8){
      return substr(str_shuffle('1234567890abcdefghijklmnopqrstuvwxyz'), 0, $length);
    }
    $isUpdated = false;
    if(!empty($newUsername) && !empty($newPassword)){
      foreach($userInfo as $user){
        if($user['name'] == $newUsername){
          $isUpdated = true;
          return array("error" => "すでに登録されているユーザー名です");
        }
      }
      if(!$isUpdated){
        $salt = createSalt();
        $encrypted_password = crypt($newPassword, $salt);
        $userdb->insertUser($newUsername, $salt, $encrypted_password);
        return array(
            "message" => "新規登録完了です"
        );
      }
    }else{
      return array(
          "error" => "入力してください"
      );
    }
  }

  public static function login($username, $password){
    $userdb = new UserMapper();
    $userInfo = $userdb->selectFromName($username);
    if($userInfo == null){
      return array(
          "error" => "登録されていない名称です"
      );
    }
    $userId = $userInfo[0]['id'];
    $userEncrypt = $userInfo[0]['encrypted_password'];
    $xxx = crypt($password, $userInfo[0]['salt']);
    if($xxx == $userEncrypt){
      $_SESSION[$userEncrypt] = $userId;
      return array(
          "encrypted_password" => htmlspecialchars($userEncrypt)
      );
    }else{
      return array(
          "error" => "パスワードが間違っています"
      );
    }

  }

  public static function putCash($userEncrypt, $howMuchCash){
    $db = new Mapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    $result = $vm->putCash($user, $howMuchCash, $db);
    return $result;
  }

  public static function backChange($userEncrypt){
    $db = new Mapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    $vm->backChange($user, $db);
  }

  public static function charge($userEncrypt, $howMuchSuica){
    $db = new Mapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    $user->chargeSuica($howMuchSuica, $db);
  }

  public static function selectedDrink($userEncrypt, $selectedDrink){
    $db = new Mapper();
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
            return $result;
            break;
          case VendingMachine::$vm_type_Suica:
            $result = $vm->choiceSuicaDrink($drink, $drinkId);
            return $result;
            break;
          case VendingMachine::$vm_type_Both:
            if($vm->getCharge() > 0){
              $result = $vm->buyCashVm($user, $drink, $drinkId, $db);
              return $result;
            }elseif($vm->getCharge() == 0){
              $result = $vm->choiceSuicaDrink($drink, $drinkId);
              return $result;
            }
            break;
        }
        break;
      }
    }
  }

  public static function buySuica($userEncrypt){
    $db = new Mapper();
    $userId = $_SESSION[$userEncrypt];
    $vm = $_SESSION[$userId . 'SES_KEY_VM'];
    $user = $_SESSION[$userId . 'SES_KEY_USER'];
    // 自販機のdrinkArray,stockArrayの取得
    $drink_in_vending_machine = $_SESSION[$userId . 'SES_KEY_VM_DRINK_RECORD'];
    $vm->setDrinkArray($db, $drink_in_vending_machine);
    $drinkArray = $vm->getDrinks();
    $result = $vm->buySuicaVm($user, $drinkArray, $db);
    return $result;
  }

}
