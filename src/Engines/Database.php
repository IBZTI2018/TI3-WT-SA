<?php

namespace WTSA1\Engines;

// General singleton class.
class Database {
    // Hold the class instance.
    private static $instance = null;
    private $db;
    
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct($testMode = false)
    {
        define('DBHost', $testMode ? 'test_mysql' : 'mysql');
        define('DBPort', getenv('MYSQL_PORT'));
        define('DBName', getenv('MYSQL_DATABASE'));
        define('DBUser', getenv('MYSQL_USER'));
        define('DBPassword', getenv('MYSQL_PASSWORD'));

        $this->db = new \Db(DBHost, DBPort, DBName, DBUser, DBPassword);
    }
   
    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance($testMode = false)
    {
      if (self::$instance == null)
      {
        self::$instance = $testMode ? new Database(true) : new Database(false);
      }
   
      return self::$instance;
    }

    public static function setTestMode() {
      Database::getInstance(true);
    }

    public function query($query, $params = null) {
      return $this->db->query($query, $params);
    }

  }

?>
