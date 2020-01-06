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
    array('J_duudDun',      PBKDF2::generate('asdqwe123qweasyd')),
    array('Ligneous',       PBKDF2::generate('asdqwe123qweasyd')),
    array('ArsovEphod',     PBKDF2::generate('asdqwe123qweasyd')),
    array('Autophagy',      PBKDF2::generate('asdqwe123qweasyd')),
    array('Arguseyed',      PBKDF2::generate('asdqwe123qweasyd')),
    array('Blauwbok',       PBKDF2::generate('asdqwe123qweasyd')),
    array('Winsome',        PBKDF2::generate('asdqwe123qweasyd')),
    array('Abattoir',       PBKDF2::generate('asdqwe123qweasyd')),
    array('Frangipani',     PBKDF2::generate('asdqwe123qweasyd')),
    array('Casco11Esker',   PBKDF2::generate('asdqwe123qweasyd')),
    array('Polysarcous',    PBKDF2::generate('asdqwe123qweasyd')),
    array('Acrimony',       PBKDF2::generate('asdqwe123qweasyd')),
    array('Abderian',       PBKDF2::generate('asdqwe123qweasyd')),
    array('LesterdPatzer',  PBKDF2::generate('asdqwe123qweasyd')),
    array('Moleskin',       PBKDF2::generate('asdqwe123qweasyd')),
    array('Luminous',       PBKDF2::generate('asdqwe123qweasyd')),
    array('Goodfella',      PBKDF2::generate('asdqwe123qweasyd')),
    array('Musteline',      PBKDF2::generate('asdqwe123qweasyd')),
    array('Imbricate',      PBKDF2::generate('asdqwe123qweasyd'))
  );
  Database::getInstance()->query("
    INSERT INTO `user` (username, password) VALUES
      ". implode(", ", array_map(function($item) { return "('".$item[0]."','".$item[1]."')"; }, $dummyData)) . "
    ;
  ");
  
  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseSeed();

?>
