<?php

require_once __DIR__ . "/CustomTestCase.php";

class DatabaseSetupTest extends CustomTestCase
{

    public function testUserTableExists()
    {
        $query = \WTSA1\Engines\Database::getInstance()->query("SELECT 1 FROM `user` LIMIT 1;");
        $this->assertSame(1, $query[0][1]);
    }

    public function testUsernameConstraint()
    {
        $this->expectException(PDOException::class);
        $query1 = \WTSA1\Engines\Database::getInstance()->query("
        INSERT INTO `user` (username, password) VALUES
            ('username', 'password'),
            ('username', 'password');
        ");
    }
}
?>