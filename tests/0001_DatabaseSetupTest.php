<?php
use PHPUnit\Framework\TestCase;

class DatabaseSetupTest extends TestCase
{
    public function testUserTableExists()
    {
        $query = \WTSA1\Database::getInstance()->query("SELECT 1 FROM `user` LIMIT 1;");
        $this->assertSame(1, $query[0][1]);
    }

    public function testUsernameConstraint()
    {
        $this->expectException(PDOException::class);
        $query1 = \WTSA1\Database::getInstance()->query("
        INSERT INTO `user` (username, password) VALUES
            ('username', 'password'),
            ('username', 'password');
        ");
    }
}
?>