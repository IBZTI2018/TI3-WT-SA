<?php

namespace WTSA1\Models;
use WTSA1\Engines\Database;
use WTSA1\Engines\Hasher\PBKDF2;

class User {
    private $_id;
    private $_username;
    private $_password;

    // Getters
    public function getId() { return $this->_id; }
    public function getUsername() { return $this->_username; }
    public function getPassword() { return $this->_password; }

    // Setters
    public function setId($id) { 
        $this->_id = $id; 
    }
    public function setUsername($username) { 
        $this->_username = $username; 
    }
    public function setPassword($password) {
        $this->_password = $password;
    }

    // Functions

    public function __construct(
        $id = null,
        $username = null,
        $password = null
    ) {
        $this->_id = $id;
        $this->_username = $username;
        $this->_password = $password;
    }

    /**
     * Get a user from the database by its id
     * 
     * @param int $id The id of the user to search for
     * @return User|null The user object or null if not found
     */
    public function getById($id) {
        $result = Database::getInstance()->query("
                SELECT * FROM `user` WHERE id = ?
            ",
            array($id)
        );
        return self::parse($result);
    }

    /**
     * Login with given user credentials
     * 
     * @param string $username The username
     * @param string $password The password
     * @return User|null The logged in user or null if invalid credentials
     */
    public static function login($username, $password) {
        $result = Database::getInstance()->query("
                SELECT * FROM `user` WHERE username = ?;
            ", 
            array($username)
        );

        $user = self::parse($result);

        if ($user == null) return null;

        // PBKDF2 Bundle
        $bundle = explode("$", $user->getPassword());
        $algorithm = $bundle[0];
        $iterations = $bundle[1];
        $salt = $bundle[2];

        if (PBKDF2::verify($user->getPassword(), $password, $algorithm, $iterations, $salt) != true) {
            $user = null;
        }

        return $user;
    }

    private static function parse($result) {
        if (count($result) > 0) {
            $u = $result[0];
            $user = new User($u['id'], $u['username'], $u['password']);
            return $user;
        }
        return null;
    }
}

?>