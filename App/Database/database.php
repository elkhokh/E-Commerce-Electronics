<?php
namespace App\Database;

use PDO;
use PDOException;

class Database  {
    private static ?Database $instance = null;
    private  PDO $connection;
  private function __construct(array $config)  {
    try {
        $dsn="mysql: host={$config['host']};dbname={$config['dbname']}";
        $this->connection=new PDO($dsn,$config['username'],$config['password']);
    } catch (PDOException $th) {
        header('Location: index.php?page=Maintenance');
        exit;
    }
    
  } 
  static function get_instance(array $config):Database  {
    if (self::$instance==null) {
        self::$instance=new self($config);
    }
    return self::$instance;
  }
  
  function get_connection() : PDO {
    return $this->connection;
    
  }
}
