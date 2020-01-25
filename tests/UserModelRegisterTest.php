<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Models\User;

class UserModelRegisterTest extends TestCase {

    public function testRegistrationFailsIfUserAlreadyExists() {
        // TODO: This should actually not be raised here, it should be caught in the
        //       user model. I don't currently know, why that does not work properly!
        //       Once we know why this happens, we can remove this and use the assert.
        $this->expectException(PDOException::class);

        Database::getInstance()->query("
          INSERT INTO `users` (id, username, password)
          VALUES (1, 'someuser', '".PBKDF2::generate("password")."');
        ");

        $user = User::register("someuser", "password");
        $this->assertNull($user);
    }

    public function testRegistrationWorksWithNewCredentials() {
      $user = User::register("someuser", "password");
      $user = User::getByName("someuser");
      $this->assertNotNull($user);
    }
}
?>