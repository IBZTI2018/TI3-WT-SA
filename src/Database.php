<?php

namespace WTSA1;

// General singleton class.
class Database {
    // Hold the class instance.
    private static $instance = null;
    private $db;
    
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        define('DBHost', 'mysql');
        define('DBPort', 3306);
        define('DBName', getenv('MYSQL_DATABASE'));
        define('DBUser', getenv('MYSQL_USER'));
        define('DBPassword', getenv('MYSQL_PASSWORD'));

        $this->db = new \Db(DBHost, DBPort, DBName, DBUser, DBPassword);
    }
   
    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
      if (self::$instance == null)
      {
        self::$instance = new Database();
      }
   
      return self::$instance;
    }

    public function query($query, $params = null) {
      return $this->db->query($query, $params);
    }

  }

?>
