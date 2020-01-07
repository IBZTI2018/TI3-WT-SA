<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Pages\LoginPage;
use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class PageLoginTestee extends LoginPage {
    public function testPost() {
        $this->post();
    }

    public function dumpData() {
        return $this->data;
    }
}

class PageLoginTest extends TestCase {

    public function testCreatesErrorWhenUsernameEmpty() {
        $testee = new PageLoginTestee();
        $_REQUEST = array();
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "You must provide a username!");
    }

    public function testCreatesErrorWhenPasswordEmpty() {
        $testee = new PageLoginTestee();
        $_REQUEST = array(
            "username" => "someuser"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "You must provide a password!");
    }

    public function testCreatesErrorWhenCredentialsAreInvalid() {
        $testee = new PageLoginTestee();
        $_REQUEST = array(
            "username" => "someuser",
            "password" => "password"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Login failed, please try again.");
    }

    public function testLogsInWithValidCredentials() {
        Database::getInstance()->query("
            INSERT INTO `user` (id, username, password)
            VALUES (1, 'someuser', '".PBKDF2::generate("password")."');
        ");

        $testee = new PageLoginTestee();
        $_REQUEST = array(
            "username" => "someuser",
            "password" => "password"
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