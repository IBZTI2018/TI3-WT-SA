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
        $assumedDbHost = $testMode ? 'test_mysql' : 'mysql';

        $dbHost = (!empty(getenv('MYSQL_HOST'))) ? getenv('MYSQL_HOST') : $assumedDbHost;
        $dbPort = getenv('MYSQL_PORT');
        $dbName = getenv('MYSQL_DATABASE');
        $dbUser = getenv('MYSQL_USER');
        $dbPass = getenv('MYSQL_PASSWORD');

        if (empty($dbPort)) die("MYSQL_PORT not defined!");
        if (empty($dbName)) die("MYSQL_DATABASE not defined!");
        if (empty($dbUser)) die("MYSQL_USER not defined!");
        if (empty($dbPass)) die("MYSQL_PASSWORD not defined!");

        $this->db = new \Db($dbHost, $dbPort, $dbName, $dbUser, $dbPass);
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
