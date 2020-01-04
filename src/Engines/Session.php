<?php

namespace WTSA1\Engines;

use WTSA1\Models\User;

// General singleton class.
class Session {
    // Hold the class instance.
    private static $instance = null;
    
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        session_start();
    }
   
    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
      if (self::$instance == null)
      {
        self::$instance = new Session();
      }
   
      return self::$instance;
    }

    public function setUser($user) {
      $_SESSION['user.id'] = $user->getId();
    }

    public function getUser()
    {
      if (isset($_SESSION['user.id'])) {
        return User::getById($_SESSION['user.id']);
      }
      return null;
    }

  }

?>
