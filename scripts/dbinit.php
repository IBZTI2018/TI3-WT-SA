<?php 

##########
# Script for setting up the database for the agenda
##########

use WTSA1\Engines\Database;

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
      `password` varchar(255) NOT NULL DEFAULT '',
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
  Database::getInstance()->query("
  INSERT INTO `user` (username, password) VALUES
    ('J_duudDun', 'asdqwe123qweasyd'),
    ('Ligneous', 'asdqwe123qweasyd'),
    ('ArsovEphod', 'asdqwe123qweasyd'),
    ('Autophagy', 'asdqwe123qweasyd'),
    ('Arguseyed', 'asdqwe123qweasyd'),
    ('Blauwbok', 'asdqwe123qweasyd'),
    ('Winsome', 'asdqwe123qweasyd'),
    ('Abattoir', 'asdqwe123qweasyd'),
    ('Frangipani', 'asdqwe123qweasyd'),
    ('Casco11Esker', 'asdqwe123qweasyd'),
    ('Polysarcous', 'asdqwe123qweasyd'),
    ('Acrimony', 'asdqwe123qweasyd'),
    ('Abderian', 'asdqwe123qweasyd'),
    ('LesterdPatzer', 'asdqwe123qweasyd'),
    ('Moleskin', 'asdqwe123qweasyd'),
    ('Luminous', 'asdqwe123qweasyd'),
    ('Goodfella', 'asdqwe123qweasyd'),
    ('Musteline', 'asdqwe123qweasyd'),
    ('Imbricate', 'asdqwe123qweasyd');
  ;
  ");
  
  echo "Done!\r\n";
  echo "\r\n";
}

if (php_sapi_name() == "cli") {
  dbinit();
}

?>