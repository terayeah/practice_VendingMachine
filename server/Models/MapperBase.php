<?php
define('DB_DATABASE', 'vending_machine');
define('DB_USERNAME', 'terai');
define('DB_PASSWORD', 'terai');
define('PDO_DSN', 'mysql:host=localhost;unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=' . DB_DATABASE);
define('_DIR_', '/opt/local/www/apache2/html/lessons/a_vending_machine');


class MapperBase{

  protected $db;

  public function __construct(){
    $this->db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  }

  public function select($table, $data = array()) {
    try {
      $stmt = $this->db->prepare("select * from " . $table);
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

  public function insert($table, $data = array()) {
    try {
      $stmt = $this->db->prepare("insert into " . $table);
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

  public function update($table, $change, $data = array(), $id) {
    try {
      $stmt = $this->db->prepare("update " . $table . " set " . $change . " where id = " . $id);
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


}
