<?php 

/**
 * Seed the database for development
 * 
 * This will seed the database for use in the development environment.
 * Seeding should not be used in the test environment!
 */

use WTSA1\Engines\Database;
use WTSA1\Engines\Hasher\PBKDF2;

// Autoload source and dependencies via composer
require_once __DIR__ . '/../../vendor/autoload.php';

function databaseSeed()  {

  echo "Seeding user table...\r\n";
  $dummyData = array(
    array('sgehring', PBKDF2::generate('password')),
    array('aglatzl', PBKDF2::generate('password')),
    array('aschild', PBKDF2::generate('password'))
  );
  Database::getInstance()->query("
    INSERT INTO `user` (username, password) VALUES
      ". implode(", ", array_map(function($item) { return "('".$item[0]."','".$item[1]."')"; }, $dummyData)) . "
    ;
  ");

  echo "Seeding category table...\r\n";
  $dummyData = array(
    array(1, "Unkategorisiert"),
    array(2, "Ferien"),
    array(3, "Geburtstag"),
    array(4, "Familienfest"),
    array(5, "Ausflug")
  );
  Database::getInstance()->query("
    INSERT INTO `category` (id, category) VALUES
      ". implode(", ", array_map(function($item) { return "('".$item[0]."','".$item[1]."')"; }, $dummyData)) . "
    ;
  ");
  
  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseSeed();

?>
