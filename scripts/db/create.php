<?php 

/**
 * Create the database for running the diary software
 * 
 * This will create all required tables, associations and constraints.
 */

use WTSA1\Engines\Database;
use WTSA1\Engines\Hasher\PBKDF2;

// Autoload source and dependencies via composer
require_once __DIR__ . '/../../vendor/autoload.php';

function databaseCreate() {
  
  echo "Creating `user` table...\r\n";
  Database::getInstance()->query("
    CREATE TABLE `user` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `username` varchar(255) NOT NULL DEFAULT '',
      `password` text NOT NULL,
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=latin1;
  ");

  echo "Adding unique constraint on user table for username field...\r\n";
  Database::getInstance()->query("
    ALTER TABLE `user`
      ADD CONSTRAINT constr_username UNIQUE (username);
  ");

  echo "Creating `category` table...\r\n";
  Database::getInstance()->query("
    CREATE TABLE `category` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `category` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=latin1;
  ");

  echo "Adding unique constraint on category table for category field...\r\n";
  Database::getInstance()->query("
    ALTER TABLE `category`
      ADD CONSTRAINT constr_category UNIQUE (category);
  ");

  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseCreate();

?>
