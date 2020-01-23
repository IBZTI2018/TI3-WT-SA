<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\User;
use WTSA1\Models\DiaryEntry;

class DiaryEntryModelTest extends TestCase {

    public function setUp(): void {
        Database::getInstance()->query("
          INSERT INTO `users` (id, username, password) VALUES
            (1, 'someuser', '".PBKDF2::generate("password")."');
        ");
        Database::getInstance()->query("
          INSERT INTO `categories` (id, category) VALUES
            (1, 'uncategorized');
        ");
    }

    public function testDoesNotListEntriesForOtherUser() {
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content');
      ");

      Session::getInstance()->setUser(User::login("someuser", "password"));
      $entries = DiaryEntry::getEntriesForCurrentUser();

      $this->assertEquals($entries, array());
    }

    public function testDoesListAllEntriesForCurrentUser() {
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content'),
          (2, 1, '2020-01-01', 'some content'),
          (1, 1, '2020-01-01', 'some other content');
      ");

      Session::getInstance()->setUser(User::login("someotheruser", "password"));
      $entries = DiaryEntry::getEntriesForCurrentUser();

      $this->assertEquals(count($entries), 2);
    }

    public function testForeignKeyViolationUserIdOnDiaryEntry() {
        $this->expectException(PDOException::class);
        Database::getInstance()->query("
          INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (9999999, 1, '2020-01-01', 'constraint');
        ");
    }
    
    public function testForeignKeyAcceptanceUserIdOnDiaryEntry() {
        Database::getInstance()->query("
          INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (1, 1, '2020-01-01', 'constraint');
        ");
        $this->assertNull(null);
    } 
    
    public function testForeignKeyViolationCategoryIdOnDiaryEntry() {
        $this->expectException(PDOException::class);
        Database::getInstance()->query("
            INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (1, 9999999, '2020-01-01', 'constraint');
        ");
    }
    
    public function testForeignKeyAcceptanceCategoryIdOnDiaryEntry() {
        Database::getInstance()->query("
            INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (1, 1, '2020-01-01', 'constraint');
        ");
        $this->assertNull(null);
    }
}
?>
