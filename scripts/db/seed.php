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

function _valuesForData($list) {
  $tuples = array_map(function($tuple) {
    $values = array_map(function($value) {
      if (is_numeric($value)) return $value;
      return "'".$value."'";
    }, $tuple);
    return "(".join($values, ",").")";
  }, $list);

  return join($tuples, ",\n");
}

function databaseSeed()  {

  echo "Seeding user table...\r\n";
  $data = array(
    array(1, 'sgehring', PBKDF2::generate('password')),
    array(2, 'aglatzl', PBKDF2::generate('password')),
    array(3, 'aschild', PBKDF2::generate('password'))
  );
  Database::getInstance()->query("INSERT INTO `user` (id, username, password) VALUES "._valuesForData($data).";");

  echo "Seeding category table...\r\n";
  $data = array(
    array(1, "Unkategorisiert"),
    array(2, "Ferien"),
    array(3, "Geburtstag"),
    array(4, "Familienfest"),
    array(5, "Ausflug")
  );
  Database::getInstance()->query("INSERT INTO `category` (id, category) VALUES "._valuesForData($data).";");

  echo "Seeding diary entry table...\r\n";
  $data = array(
    array(1, 1, "2020-01-01", "Ein eintrag mit einem relativ langen text, der irgend einmal umgebrochen werden muss, weil er sonst viel zu lang wäre!"),
    array(1, 2, "2020-01-02", "Noch ein Eintrag"),
    array(1, 2, "2020-01-03", "Noch einer"),
    array(1, 3, "2020-01-04", "Und noch einer"),
    array(3, 1, "2020-01-01", "Bewertungswitz")
  );
  Database::getInstance()->query("
    INSERT INTO `diary_entry` (user_id, category_id, publish_date, content) VALUES "._valuesForData($data).";
  ");
  
  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseSeed();

?>
