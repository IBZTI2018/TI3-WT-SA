<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Pages\ImagePage;
use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Session;
use WTSA1\Engines\Database;
use WTSA1\Models\Entry;
use WTSA1\Models\User;

class PageImageTestee extends ImagePage {
    public function testGet() {
        $this->get();
    }

    public function dumpData() {
        return $this->data;
    }
}

class PageImageTest extends TestCase {

    public function setUp(): void {
        Database::getInstance()->query("
          INSERT INTO `categories` (id, category) VALUES
            (1, 'uncategorized');
        ");
        Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (1, 'someotheruser', '".PBKDF2::generate("password")."'),
          (2, 'someotheruser2', '".PBKDF2::generate("password")."');
        ");
        Database::getInstance()->query("
            INSERT INTO `entries` (id, user_id, category_id, publish_date, content) VALUES
            (1, 1, 1, '2020-01-01', 'some content')
        ");
    }

    public function testCreatesErrorWhenEntryIdUndefiend() {
        try {
            Session::getInstance()->setUser(User::login("someotheruser2", "password"));
        } catch(Exception $e) {}
        $testee = new PageImageTestee();
        $_REQUEST = array();
        $testee->testGet();
        $this->assertEquals($testee->dumpData()["error"], "Beim Laden des Bildes ist ein Fehler aufgetretten.");
    }

    public function testCreatesErrorWhenEntryIdEmpty() {
        try {
            Session::getInstance()->setUser(User::login("someotheruser2", "password"));
        } catch(Exception $e) {}
        $testee = new PageImageTestee();
        $_REQUEST = array('entry_id' => null);
        $testee->testGet();
        $this->assertEquals($testee->dumpData()["error"], "Beim Laden des Bildes ist ein Fehler aufgetretten.");
    }

    public function testCreatesErrorWhenEntryObjectEmpty() {
        try {
            Session::getInstance()->setUser(User::login("someotheruser2", "password"));
        } catch(Exception $e) {}
        $testee = new PageImageTestee();
        $_REQUEST = array('entry_id' => 9999999);
        $testee->testGet();
        $this->assertEquals($testee->dumpData()["error"], "Beim Laden des Bildes ist ein Fehler aufgetretten.");
    }

    public function testCreatesErrorWhenEntryDoesNotBelongUser() {
        try {
            Session::getInstance()->setUser(User::login("someotheruser2", "password"));
        } catch(Exception $e) {}
        $testee = new PageImageTestee();
        $_REQUEST = array('entry_id' => 1);
        $testee->testGet();
        $this->assertEquals($testee->dumpData()["error"], "Beim Laden des Bildes ist ein Fehler aufgetretten.");
    }

    public function testShowImageSuccess() {
        try {
            Session::getInstance()->setUser(User::login("someotheruser", "password"));
        } catch(Exception $e) {}
        $testee = new PageImageTestee();
        $_REQUEST = array('entry_id' => 1);
        $testee->testGet();
        $this->assertArrayHasKey('image', $testee->dumpData());
    }

}
?>