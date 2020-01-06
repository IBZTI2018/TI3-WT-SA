<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Models\User;

class UserModelLoginTest extends TestCase {

    public function testLoginFailsIfUserDoesNotExist() {
        $user = User::login("someuser", "password");
        $this->assertNull($user);
    }

    public function testLoginFailsWithWrongUsername() {
        Database::getInstance()->query("
            INSERT INTO `user` (username, password)
                VALUES ('someuser', '".PBKDF2::generate('password')."');
        ");

        $user = User::login("someotheruser", "password");
        $this->assertNull($user);
    }

    public function testLoginFailsWithWrongPassword() {
        Database::getInstance()->query("
            INSERT INTO `user` (username, password)
                VALUES ('someuser', '".PBKDF2::generate('password')."');
        ");

        $user = User::login("someuser", "wrongpassword");
        $this->assertNull($user);
    }

    public function testLoginWorksWithCorrectCredentials() {
        Database::getInstance()->query("
            INSERT INTO `user` (username, password)
                VALUES ('someuser', '".PBKDF2::generate('password')."');
        ");

        $user = User::login("someuser", "password");
        $this->assertNotNull($user->getId());
    }
}
?>