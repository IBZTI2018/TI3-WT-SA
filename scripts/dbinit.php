<?php 

##########
# Script for setting up the database for the agenda
##########

use WTSA1\Engines\Database;
use WTSA1\Engines\Hasher\PBKDF2;

// Autoload source and dependencies via composer
require_once __DIR__ . '/../vendor/autoload.php';

function dbinit() 
{
  // 1. Dropping user table
  echo "Dropping `user` table...\r\n";
  Database::getInstance()->query("DROP TABLE IF EXISTS `user`");

  // 2. Creating user table
  echo "Creating `user` table...\r\n";
  Database::getInstance()->query("
  CREATE TABLE `user` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `username` varchar(255) NOT NULL DEFAULT '',
      `password` text NOT NULL,
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=latin1;
  ");

  // 3. Adding unique constraint on user table for username field
  echo "Adding unique constraint on user table for username field...\r\n";
  Database::getInstance()->query("
  ALTER TABLE `user` ADD CONSTRAINT constr_username UNIQUE (username);
  ");

  // 4. Seeding user table
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
    " . implode(", ", array_map(function($item) { return "('".$item[0]."','".$item[1]."')"; }, $dummyData)) . "
  ;
  ");
  
  echo "Done!\r\n";
  echo "\r\n";
}

if (defined("PHPUNIT_WTSA1_TESTSUITE") != true) {
  dbinit();
}

?>