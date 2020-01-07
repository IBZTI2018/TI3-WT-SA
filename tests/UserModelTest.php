<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Models\User;

class UserModelTest extends TestCase {

    public function testReturnsNullIfUserNotFoundById() {
        $user = User::getById(1);
        $this->assertNull($user);
    }

    public function testReturnsUserIfFoundById() {
        Database::getInstance()->query("
          INSERT INTO `user` (id, username, password)
          VALUES (1, 'someuser', '".PBKDF2::generate("password")."');
        ");

        $user = User::getById(1);
        $this->assertNotNull($user);
        $this->assertEquals(get_class($user), "WTSA1\Models\User");
    }

    public function testReturnsNullIfUserNotFoundByName() {
        $user = User::getByName("someuser");
        $this->assertNull($user);
    }

    public function testReturnsUserIfFoundByname() {
        Database::getInstance()->query("
          INSERT INTO `user` (id, username, password)
          VALUES (1, 'someuser', '".PBKDF2::generate("password")."');
        ");

        $user = User::getByName("someuser");
        $this->assertNotNull($user);
        $this->assertEquals(get_class($user), "WTSA1\Models\User");
    }
}
?>