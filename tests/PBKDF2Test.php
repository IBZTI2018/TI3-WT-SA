<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Hasher\PBKDF2;

class PBKDF2Test extends TestCase {
    public function testHashIsDifferentOnDifferentEncodings() {
        $hash1 = PBKDF2::generate("password");
        $hash2 = PBKDF2::generate("password");
        $this->assertNotEquals($hash1, $hash2);
    }
}
?>