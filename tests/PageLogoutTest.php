<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Pages\LogoutPage;
use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class PageLogoutTestee extends LogoutPage {
    public function testPost() {
        $this->post();
    }
}

class PageLogoutTest extends TestCase {

    public function testInvalidatesSessionIfItExists() {
        Database::getInstance()->query("
            INSERT INTO `user` (id, username, password)
            VALUES (1, 'someuser', '".PBKDF2::generate("password")."');
        ");

        Session::getInstance()->setUser(User::login("someuser", "password"));
        $this->assertEquals(Session::getInstance()->getUser()->getUsername(), "someuser");

        $testee = new PageLogoutTestee();   
        $_REQUEST = array();
        $testee->testPost();

        $this->assertEquals(Session::getInstance()->getUser(), null);
    }

}
?>