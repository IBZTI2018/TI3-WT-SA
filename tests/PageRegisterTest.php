<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Pages\RegisterPage;
use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class PageRegisterTestee extends RegisterPage {
    public function testPost() {
        $this->post();
    }

    public function dumpData() {
        return $this->data;
    }
}

class PageRegisterTest extends TestCase {

    public function testCreatesErrorWhenUsernameEmpty() {
        $testee = new PageRegisterTestee();
        $_REQUEST = array();
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Bitte gib ein Username ein!");
    }

    public function testCreatesErrorWhenPasswordEmpty() {
        $testee = new PageRegisterTestee();
        $_REQUEST = array(
            "username" => "someuser"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Bitte gib ein Passwort ein!");
    }

    public function testCreatesErrorWhenPasswordsDontMatch() {
        $testee = new PageRegisterTestee();
        $_REQUEST = array(
            "username" => "someuser",
            "password" => "password",
            "passwordrep" => "password2"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Die Passwörter stimmen nicht überein!");
    }

    public function testCreatesErrorWhenUsernameAlreadyTaken() {
        Database::getInstance()->query("
            INSERT INTO `user` (id, username, password)
            VALUES (1, 'someuser', '".PBKDF2::generate("password")."');
        ");

        $testee = new PageRegisterTestee();
        $_REQUEST = array(
            "username" => "someuser",
            "password" => "password",
            "passwordrep" => "password"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Der angegebene Username wird bereits verwendet!");
    }

    public function testCreatesUserWithValidCredentialsAndLogsIn() {
      $testee = new PageRegisterTestee();
      $_REQUEST = array(
          "username" => "someuser",
          "password" => "password",
          "passwordrep" => "password"
      );

      try {
          $testee->testPost();
      } catch(Exception $e) {
          // This asserts that a redirect was attempted after logging in!
          $this->assertEquals(substr($e->getMessage(), 0, 32), "Cannot modify header information");
      }

      $this->assertEquals($testee->dumpData(), array());
      $this->assertEquals(Session::getInstance()->getUser()->getUsername(), "someuser");
    }
}
?>