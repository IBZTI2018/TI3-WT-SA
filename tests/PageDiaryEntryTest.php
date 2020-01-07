<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\Category;
use WTSA1\Models\User;
use WTSA1\Pages\DiaryEntryPage;

class PageDiaryEntryTestee extends DiaryEntryPage {
    public function testPost() {
        $this->post();
    }

    public function dumpData() {
        return $this->data;
    }
}

class PageDiaryEntryTest extends TestCase {

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
            INSERT INTO `category` (id, category) VALUES
            ". implode(", ", array_map(function($item) { return "('".$item[0]."','".$item[1]."')"; }, $dummyData)) . "
            ;
        ");
    }

    public function testCreatesErrorWhenPublishDateEmpty() {
        $testee = new PageDiaryEntryTestee();
        $_REQUEST = array();
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "You must specify a publish date!");
    }

    public function testCreatesErrorWhenCategoryEmpty() {
        $testee = new PageDiaryEntryTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01"
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "You must choose a category!");
    }

    public function testCreatesErrorWhenContentEmpty() {
        $testee = new PageDiaryEntryTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "You must write some content!");
    }

    public function testCreatesErrorWhenContentIsBigger1000() {
        $testee = new PageDiaryEntryTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1,
            "content" => str_repeat("$", 1001)
        );
        $testee->testPost();
        $this->assertEquals($testee->dumpData()["error"], "Your content can't have more than 1000 characters!");
    }

    public function testCreatesSuccessfully() {
        $testee = new PageDiaryEntryTestee();
        $_REQUEST = array(
            "publish_date" => "2020-01-01",
            "category" => 1,
            "content" => str_repeat("$", 999)
        );
        $testee->testPost();
        $this->assertArrayNotHasKey("error", $testee->dumpData());
    }
}
?>