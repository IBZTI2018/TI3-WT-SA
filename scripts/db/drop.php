<?php 

/**
 * Drop the database for  the diary software
 * 
 * This will drop all tables and constraints.
 */

use WTSA1\Engines\Database;
use WTSA1\Engines\Hasher\PBKDF2;

// Autoload source and dependencies via composer
require_once __DIR__ . '/../../vendor/autoload.php';

function databaseDrop() {
  
  echo "Dropping `user`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `user`
  ");

  echo "Dropping `category`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `category`
  ");

  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseDrop();

?>
