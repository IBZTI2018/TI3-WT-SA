<?php

namespace Vendor;

include_once __DIR__ . '/../scripts/db/drop.php';
include_once __DIR__ . '/../scripts/db/create.php';

use PHPUnit\Runner\BeforeFirstTestHook;
use PHPUnit\Runner\AfterLastTestHook;
use PHPUnit\Runner\BeforeTestHook;
use PHPUnit\Runner\AfterTestHook;
use \WTSA1\Engines\DatabaseTestMode;
use \WTSA1\Engines\Database;
use \WTSA1\Engines\Session;

final class CustomTestRunnerHooks implements BeforeFirstTestHook, AfterLastTestHook, BeforeTestHook, AfterTestHook {
  public function executeBeforeFirstTest(): void {
    Database::getInstance(DatabaseTestMode::Enabled);
    Database::getInstance()->query("SET autocommit = OFF;");
    databaseDrop();
    databaseCreate();
  }

  public function executeAfterLastTest(): void {
    Database::getInstance(DatabaseTestMode::Disabled);
    Database::getInstance()->query("SET autocommit = ON;");
    databaseDrop();
  }

  public function executeBeforeTest($test): void {
    Database::getInstance()->startTransaction();
  }

  public function executeAfterTest($test, $time): void {
    Database::getInstance()->rollBack();
    Session::getInstance()->clearUser();
  }
}

?>
