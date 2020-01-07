<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Database;
use WTSA1\Models\DiaryEntry;

class DiaryEntryModelTest extends TestCase {

    public function setUp(): void {
        Database::getInstance()->query("
          INSERT INTO `user` (id, username, password) VALUES
            (1, 'tmp', 'tmp');
        ");
        Database::getInstance()->query("
          INSERT INTO `category` (id, category) VALUES
            (1, 'tmp');
        ");
    }

    public function testForeignKeyViolationUserIdOnDiaryEntry() {
        $this->expectException(PDOException::class);
        Database::getInstance()->query("
          INSERT INTO `diary_entry` (user_id, category_id, publish_date, content) VALUES
            (9999999, 1, '2020-01-01', 'constraint');
        ");
      }
    
      public function testForeignKeyAcceptanceUserIdOnDiaryEntry() {
        Database::getInstance()->query("
          INSERT INTO `diary_entry` (user_id, category_id, publish_date, content) VALUES
            (1, 1, '2020-01-01', 'constraint');
        ");
        $this->assertNull(null);
      } 
    
      public function testForeignKeyViolationCategoryIdOnDiaryEntry() {
        $this->expectException(PDOException::class);
        Database::getInstance()->query("
          INSERT INTO `diary_entry` (user_id, category_id, publish_date, content) VALUES
            (1, 9999999, '2020-01-01', 'constraint');
        ");
      }
    
      public function testForeignKeyAcceptanceCategoryIdOnDiaryEntry() {
        Database::getInstance()->query("
          INSERT INTO `diary_entry` (user_id, category_id, publish_date, content) VALUES
            (1, 1, '2020-01-01', 'constraint');
        ");
        $this->assertNull(null);
      }
}
?>