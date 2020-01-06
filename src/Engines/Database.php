<?php

namespace WTSA1\Engines;

/**
 * Enumerable for cleaner naming
 */
abstract class DatabaseTestMode {
  const Enabled = true;
  const Disabled = false;
}


// General singleton class.
class Database {
    // Hold the class instance.
    private static $instance = null;
    private $db;
    
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct($testMode = DatabaseTestMode::Disabled)
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
   
    /**
     * Get the singleton database instance
     * 
     * This will create an instance in case it does not exist
     * @param bool $testMode Wether or not to enable test mode
     * @todo The parameter is only applied sometimes. - Confusing?
     */
    public static function getInstance($testMode = DatabaseTestMode::Disabled) {
      if (self::$instance == null) self::$instance = new Database($testMode);
      return self::$instance;
    }

    /**
     * Query the database
     * 
     * @param string $query The query to execute
     * @param array $params The query parameters
     */
    public function query($query, $params = null) {
      return $this->db->query($query, $params);
    }

    /**
     * Start a new database transaction
     */
    public function startTransaction() {
      $this->db->beginTransaction();
    }

    /**
     * Commit an existing transaction
     */
    public function commit() {
      $this->db->commit();
    }

    /**
     * Roll back an existing transaction
     */
    public function rollback() {
      $this->db->rollback();
    }
  }

?>
