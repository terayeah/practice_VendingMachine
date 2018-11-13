<?php
define('DB_DATABASE', 'vending_machine');
define('DB_USERNAME', 'terai');
define('DB_PASSWORD', 'terai');
define('PDO_DSN', 'mysql:host=localhost;unix_socket=/opt/local/var/run/mysql56/mysqld.sock;dbname=' . DB_DATABASE);
define('_DIR_', '/opt/local/www/apache2/html/lessons/a_vending_machine');


class MapperBase{

  protected $db;
  protected $sql;
  protected $stmt;

  public function __construct(){
    $this->db = new PDO(PDO_DSN, DB_USERNAME, DB_PASSWORD);
  }

  public function select($table) {
    $this->sql = "select * from " . $table;
    return $this;
  }

  public function where($column){
    $this->sql .= " where " . $column;
    return $this;
  }

  public function and($column){
    $this->sql .= " and " . $column;
    return $this;
  }

  public function content($data = array()){
    $this->stmt = $this->db->prepare($this->sql);
    $this->stmt->execute($data);
    return $this;
  }

  public function fetchAll(){
    return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public function insert($table){
    $this->sql = "insert into " . $table;
    return $this;
  }

  public function values($values){
    $this->sql .= " values " . $values;
    return $this;
  }

  public function update($table){
    $this->sql = "update " . $table;
    return $this;
  }

  public function setCol($column){
    $this->sql .= " set " . $column;
    return $this;
  }

  public function delete($table){
    $this->sql = "delete from " . $table;
    return $this;
  }

  public function beginTransaction() {
    $this->db->beginTransaction();
  }

  public function commit() {
    $this->db->commit();
  }


}
