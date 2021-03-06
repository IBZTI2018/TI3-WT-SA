<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;
use WTSA1\Engines\Database;

class DatabaseConstaintsTest extends TestCase {
  public function testUsernameUniqueConstraint() {
    $this->expectException(PDOException::class);
    Database::getInstance()->query("
      INSERT INTO `users` (username, password) VALUES
        ('username', 'password'),
        ('username', 'password');
    ");
  }

  public function testCategoryUniqueConstraint() {
    $this->expectException(PDOException::class);
    Database::getInstance()->query("
      INSERT INTO `categories` (category) VALUES
        ('samecategory'),
        ('samecategory');
    ");
  }
}
?>