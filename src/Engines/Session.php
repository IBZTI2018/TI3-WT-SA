<?php

namespace WTSA1\Engines;

use WTSA1\Models\User;

// General singleton class.
class Session {
    // Hold the class instance.
    private static $instance = null;
    
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct() {
        if (!defined("PHPUNIT_WTSA1_TESTSUITE")) session_start();
    }

    /**
     * Get the singleton session instance
     */
    public static function getInstance() {
      if (self::$instance == null) self::$instance = new Session();
      return self::$instance;
    }

    /**
     * Terminate an existing session
     */
    public function terminate() {
      if (!defined("PHPUNIT_WTSA1_TESTSUITE")) session_destroy();
      $this->clearUser();
      self::$instance = null;
    }

    /**
     * Store a user id in a session cookie
     * 
     * @param User $user The user object to store
     * @return void
     */
    public function setUser($user) {
      $_SESSION['user.id'] = $user->getId();
    }

    /**
     * Get the stored user from a session by id
     * 
     * @return User|null The stored user or null
     */
    public function getUser() {
      if (isset($_SESSION['user.id'])) {
        return User::getById($_SESSION['user.id']);
      }
      return null;
    }

    /**
     * Clear the user id in a session cookie
     * 
     * @return void
     */
    public function clearUser() {
      unset($_SESSION['user.id']);
    }

  }

?>
