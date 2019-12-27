<?php

require_once __DIR__ . "/CustomTestCase.php";

class LoginTest extends CustomTestCase
{
    public function testLogin()
    {
        // Try to login with userId 15
        $user = \WTSA1\Models\User::login("Moleskin", "asdqwe123qweasyd");
        $this->assertSame(15, $user->getId());
    }
}
?>