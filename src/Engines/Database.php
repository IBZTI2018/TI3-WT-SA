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

        if (strlen(DBHost) < 1 || 
            strlen(DBPort) < 1 || 
            strlen(DBName) < 1 || 
            strlen(DBUser) < 1 || 
            strlen(DBPassword) < 1) 
        {
          die("The MYSQL environment variables are not defined!");
        }

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

    /**
     * Query the database and output the query details
     * 
     * Used for debugging purposes and scripts
     * @param string $query The query to execute
     * @param array $params The query parameters
     * @return void
     */
    public function debugQuery($query, $params = null) {
      $paramDump = array();
      if (is_array($params)) {
        foreach($params as $pkey => $pval) array_push($paramDump, $pkey."=".$pval); 
      }

      echo trim($query) . join($paramDump, " ") . "\n";
      $this->query($query, $params);
    }
  }

?>
