<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\Category;
use WTSA1\Models\User;
use WTSA1\Pages\CreatePage;

class PageCreateTestee extends CreatePage {
    public function testPost() {
        $this->post();
    }

    public function dumpData() {
        return $this->data;
    }
}

class PageCreateTest extends TestCase {

    public function setUp(): void {
        Session::getInstance()->setUser(User::register("test", "test"));
        echo "Seeding category table...\r\n";
        $dummyData = array(
            array(1, "Nicht kategorisiert"),
            array(2, "Ferien"),
            array(3, "Geburtstag"),
            array(4, "Familienfest"),
            array(5, "Ausflug")
        );
        Database::getInstance()->query("
            INSERT INTO `categories` (id, category) VALUES
            ". implode(", ", array_map(function($item) { return "('".$item[0]."','".$item[1]."')"; }, $dummyData)) . "
            ;
        ");
    }

    public function testCreatesErrorWhenPublishDateEmpty() {
        $testee = new PageCreateTestee();
        $_REQUEST = array();
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Du musst ein Veröffentlichungsdatum angeben!");
    }

    public function testCreatesErrorWhenCategoryEmpty() {
        $testee = new PageCreateTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Du musst eine Kategorie auswählen!");
    }

    public function testCreatesErrorWhenContentEmpty() {
        $testee = new PageCreateTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Du musst ein Inhalt schreiben!");
    }

    public function testCreatesErrorWhenContentIsBigger1000() {
        $testee = new PageCreateTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1,
            "content" => str_repeat("$", 1001)
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Dein Inhalt darf nicht mehr als 1000 Zeichen betragen!");
    }

    public function testCreatesSuccessfully() {
        $testee = new PageCreateTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1,
            "content" => str_repeat("$", 999)
        );
        $testee->testPost();
        $this->assertArrayNotHasKey("error", $testee->dumpData());

        $count = Database::getInstance()->query("SELECT COUNT(*) FROM entries;");
        $this->assertEquals($count[0]["COUNT(*)"], 1);
    }

    public function testCreatesSuccessfullyWithOptionalImage() {
        $testee = new PageCreateTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1,
            "content" => str_repeat("$", 999),
            "image" => "thisblobisnotvalidbutitisstoredinthedatabase"
        );
        $testee->testPost();
        $this->assertArrayNotHasKey("error", $testee->dumpData());
        
        $count = Database::getInstance()->query("SELECT COUNT(*) FROM entries;");
        $this->assertEquals($count[0]["COUNT(*)"], 1);
    }
}
?>