<?php

include_once __DIR__ . '/../scripts/dbinit.php';

use PHPUnit\Framework\TestCase;

class CustomTestCase extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        \WTSA1\Engines\Database::setTestMode();
        dbinit();
    }

    public function testIsTestMode()
    {
        $this->assertSame(1, 1);
    }

}
?>