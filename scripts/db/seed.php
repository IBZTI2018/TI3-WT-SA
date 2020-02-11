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

  echo "Seeding users table...\r\n";
  $data = array(
    array(1, 'sgehring', PBKDF2::generate('password')),
    array(2, 'aglatzl', PBKDF2::generate('password')),
    array(3, 'dahmeti', PBKDF2::generate('password')),
    array(4, 'jmentner', PBKDF2::generate('password')),
    array(5, 'ckoller', PBKDF2::generate('password')),
    array(6, 'alutvia', PBKDF2::generate('password')),
    array(7, 'aschild', PBKDF2::generate('password'))
  );
  Database::getInstance()->query("INSERT INTO `users` (id, username, password) VALUES "._valuesForData($data).";");

  echo "Seeding categories table...\r\n";
  $data = array(
    array(1, "Unkategorisiert"),
    array(2, "Ferien"),
    array(3, "Geburtstag"),
    array(4, "Familienfest"),
    array(5, "Ausflug")
  );
  Database::getInstance()->query("INSERT INTO `categories` (id, category) VALUES "._valuesForData($data).";");

  echo "Seeding diary entry table...\r\n";
  $data = array(
    array(1, 1, "2019-12-09", "Heute haben wir den ersten Sprint für unsere WT1 Case-Study begonnen.", NULL),
    array(1, 1, "2019-12-20", "Nach zwei Wochen haben wir den ersten Sprint für unsere Case-Study beendet. Ich denke über die Festtage wird nicht all zu viel passieren, aber mal sehen...", NULL),
    array(1, 2, "2020-12-24", "Endlich ist Weihnachten!", NULL),
    array(7, 2, "2019-06-20", "Nachdem ich die Case-Study Aufgabenstellung fertig geschrieben habe, kann ich endlich in die Ferien. Schön, ein paar Wochen Ruhe von all den Studenten zu haben!", NULL),
    array(7, 1, "2020-03-07", "Heute habe ich eine sehr tolle Case-Study Präsentation gesehen. Das Bewerten ist immer so anstrengend, vielleicht sollte ich einfach eine 6 hinschreiben.", NULL)
  );
  Database::getInstance()->query("
    INSERT INTO `entries` (user_id, category_id, publish_date, content, image) VALUES "._valuesForData($data).";
  ");

  // Inserting binary blob does not work with inline SQL
  $img1 = file_get_contents('./scripts/db/seed.php.jpg');
  Database::getInstance()->query("
    INSERT INTO entries (user_id, category_id, publish_date, content, image)
    VALUES (?, ?, ?, ?, ?)", array(1, 2, "2020-02-01", "Ich habe schon wieder keine Lust mehr zu arbeiten, ich glaube ich gehe direkt noch einmal in die Ferien! Die kommen bestimmt auch ein paar Tage ohne mich zurecht.", $img1));
  
  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseSeed();

?>
