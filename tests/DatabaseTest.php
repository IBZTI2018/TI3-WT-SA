<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

class DatabaseConstaintsTest extends TestCase {
  public function testUsernameUniqueConstraint() {
    $this->expectException(PDOException::class);
    \WTSA1\Engines\Database::getInstance()->query("
      INSERT INTO `user` (username, password) VALUES
        ('username', 'password'),
        ('username', 'password');
    ");
  }

  public function testCategoryUniqueConstraint() {
    $this->expectException(PDOException::class);
    \WTSA1\Engines\Database::getInstance()->query("
      INSERT INTO `category` (category) VALUES
        ('samecategory'),
        ('samecategory');
    ");
  }
}
?>