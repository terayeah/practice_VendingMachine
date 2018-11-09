<?php
define('DB_DATABASE', 'vending_machine');
define('DB_USERNAME', 'terai');
define('DB_PASSWORD', 'terai');
define('PDO_DSN', 'mysql:host=localhost;unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=' . DB_DATABASE);
define('_DIR_', '/opt/local/www/apache2/html/lessons/a_vending_machine');


class Mapper{

  private $db;

  public function __construct(){
    $this->db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  }

  public function select($sql, $data = array()) {
    try {
      $stmt = $this->db->prepare($sql);
      if ($stmt->execute($data)) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
      else {
        return false;
      }
    }
    catch (Exception $e) {
      return false;
    }
  }

  public function selectFromUser(){
    $stmt = $this->db->query("select * from users");
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userInfo;
  }

  public function selectFromVendingMachine(){
    $stmt = $this->db->query("select * from vending_machine");
    $vmInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $vmInfo;
  }

  public function selectFromDrink(){
    $stmt = $this->db->query("select * from drink");
    $drinkInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $drinkInfo;
  }

  public function selectFromUserWhereName($name){
    $stmt = $this->db->query("select * from users where name ='" . $name . "'");
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userInfo;
  }

  public function selectFromUserWhereId($id){
    $stmt = $this->db->query("select * from users where id ='" . $id . "'");
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userInfo;
  }

  public function selectFromVendingMachineWhereId($id){
    $stmt = $this->db->query("select * from vending_machine where id ='" . $id . "'");
    $userInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $userInfo;
  }

  public function selectFromVMDrinkWhereVMId($vmid){
    $stmt = $this->db->query("select * from vending_machine_drink where vending_machine_id = " . $vmid);
    $drink_in_vending_machine = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $drink_in_vending_machine;
  }

  public function selectFromUserDrinkWhereUserId($userid){
    $stmt = $this->db->query("select * from user_drink where user_id = " . $userid);
    $user_drink = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $user_drink;
  }

  // public function selectFromXOneWhereX($where, $column, $columnvalue){
  //   $stmt = $this->db->query("select * from " . $where . " where " . $column . " ='" . $columnvalue . "'");
  //   $xInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
  //   return $xInfo;
  // }

  public function insertUser($name, $salt, $encrypted_password){
    $stmt = $this->db->prepare("insert into users (name, cash, suica, salt, encrypted_password) values (:name, 5000, 2000, :salt, :encrypted_password)");
    $stmt->execute(array(':name' => $name,
                         ':salt' => $salt,
                         ':encrypted_password' => $encrypted_password
                           ));
  }

  public function insertVendingMachine($name, $type){
    $stmt = $this->db->prepare("insert into vending_machine (name, type) values (:name, :type)");
    $stmt->execute(array(':name' => $name,
                         ':type' => $type
                           ));
  }

  public function insertVendingMachineDrink($vending_machine_id, $drink_id, $drink_count){
    $stmt = $this->db->prepare("insert into vending_machine_drink (vending_machine_id, drink_id, drink_count) values (:vending_machine_id, :drink_id, :drink_count)");
    $stmt->execute(array(':vending_machine_id' => $vending_machine_id,
                         ':drink_id' => $drink_id,
                         ':drink_count' => $drink_count
                           ));
  }

  public function updateVMDrink($changeDrinkStock, $vmId, $drinkId){
    $this->db->exec("update vending_machine_drink set drink_count = " . $changeDrinkStock . " where vending_machine_id = " . $vmId . " and drink_id = " . $drinkId);
  }

  public function deleteVMDrink($vmId, $drinkId){
    $this->db->exec("delete from vending_machine_drink where vending_machine_id = " . $vmId . " and drink_id = " . $drinkId );
  }

  public function putCash($charge, $id, $userCash, $userName){
    $this->db->beginTransaction();
    $this->db->exec("update vending_machine set charge = " . $charge . " where id = '" . $id . "'");
    $this->db->exec("update users set cash = " . $userCash . " where name = '" . $userName . "'");
    $this->db->commit();
  }

  public function backChange($usercash, $username, $vmid){
    $this->db->beginTransaction();
    $this->db->exec("update users set cash = " . $usercash . " where name = '" . $username . "'");
    $this->db->exec("update vending_machine set charge = 0 where id = '" . $vmid . "'");
    $this->db->commit();
  }

  public function chargeSuica($usercash, $usersuica, $username){
    $this->db->beginTransaction();
    $this->db->exec("update users set cash = " . $usercash . " where name = '" . $username . "'");
    $this->db->exec("update users set suica = " . $usersuica . " where name = '" . $username . "'");
    $this->db->commit();
  }

  public function buyCashVm($vmcharge, $vmcash, $vmid, $vmstockarraycount, $user, $drinkId){
    $this->db->beginTransaction();
    $this->db->exec("update vending_machine set charge = " . $vmcharge . " where id = '" . $vmid . "'");
    $this->db->exec("update vending_machine set cash = " . $vmcash . " where id = '" . $vmid . "'");
    $this->db->exec("update vending_machine_drink set drink_count = " . $vmstockarraycount . " where vending_machine_id = " . $vmid . " and drink_id = " . $drinkId);
    if($user->getOwnDrinkCount($drinkId) == 1){
        $this->db->exec("insert into user_drink (user_id, drink_id, drink_count) values (" . $user->getId() . ", " . $drinkId . ", 1)");
      }else{
        $this->db->exec("update user_drink set drink_count = " . $user->getOwnDrinkCount($drinkId) . " where user_id = " . $user->getId() . " and drink_id = " . $drinkId);
      }
    $this->db->commit();
  }

  public function buySuicaVm($vmsuica, $vmid, $vmstockarraycount, $user, $drinkId){
    $this->db->beginTransaction();
    $this->db->exec("update vending_machine set suica = " . $vmsuica . " where id = '" . $vmid . "'");
    $this->db->exec("update vending_machine_drink set drink_count = " . $vmstockarraycount . " where vending_machine_id = " . $vmid . " and drink_id = " . $drinkId);
    $this->db->exec("update users set suica = " . $user->getSuica() . " where name = '" . $user->getName() . "'");
    if($user->getOwnDrinkCount($drinkId) == 1){
        $this->db->exec("insert into user_drink (user_id, drink_id, drink_count) values (" . $user->getId() . ", " . $drinkId . ", 1)");
      }else{
        $this->db->exec("update user_drink set drink_count = " . $user->getOwnDrinkCount($drinkId) . " where user_id = " . $user->getId() . " and drink_id = " . $drinkId);
      }
    $this->db->commit();
    }


}
