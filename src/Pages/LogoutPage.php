<?php

namespace WTSA1\Pages;
use WTSA1\Engines\Session;

class LogoutPage extends Page {
    protected function post() {
        Session::getInstance()->terminate();
        if (!defined("PHPUNIT_WTSA1_TESTSUITE")) header("Location: /");
    }
}

?>
