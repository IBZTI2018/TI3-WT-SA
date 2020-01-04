<?php

require_once __DIR__ . "/CustomTestCase.php";

class PBKDF2Test extends CustomTestCase
{
    public function testIsPBKDF2()
    {
        // Try to login with userId 15
        $user = \WTSA1\Models\User::login("Moleskin", "asdqwe123qweasyd");
        $this->assertStringContainsString("pbkdf2_sha256", $user->getPassword());
    }

    public function testDiffHash()
    {
        $user15 = \WTSA1\Models\User::login("Moleskin", "asdqwe123qweasyd");
        $user16 = \WTSA1\Models\User::login("Luminous", "asdqwe123qweasyd");
        $this->assertNotEquals($user15->getPassword(), $user16->getPassword());
    }
}
?>