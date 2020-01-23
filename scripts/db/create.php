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
  
  echo "Creating `users` table...\r\n";
  Database::getInstance()->query("
    CREATE TABLE `users` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `username` varchar(255) NOT NULL DEFAULT '',
      `password` text NOT NULL,
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=latin1;
  ");

  echo "Adding unique constraint on user table for username field...\r\n";
  Database::getInstance()->query("
    ALTER TABLE `users`
      ADD CONSTRAINT constr_username UNIQUE (username);
  ");

  echo "Creating `category` table...\r\n";
  Database::getInstance()->query("
    CREATE TABLE `categories` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `category` varchar(255) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=latin1;
  ");

  echo "Adding unique constraint on category table for category field...\r\n";
  Database::getInstance()->query("
    ALTER TABLE `categories`
      ADD CONSTRAINT constr_category UNIQUE (category);
  ");

  echo "Creating `entries` table...\r\n";
  Database::getInstance()->query("
    CREATE TABLE `entries` (
      `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
      `user_id` int(11) unsigned NOT NULL,
      `category_id` int(11) unsigned NOT NULL DEFAULT '1',
      `publish_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `content` varchar(1000) NOT NULL DEFAULT '',
      PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=latin1;  
  ");

  echo "Adding foreign key relationship between entries.user_id and user.id...\r\n";
  Database::getInstance()->query("
    ALTER TABLE `entries`
    ADD CONSTRAINT `fk_entries.user_id__user.id`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;
  ");

  echo "Adding foreign key relationship between entries.category_id and category.id...\r\n";
  Database::getInstance()->query("
    ALTER TABLE `entries`
    ADD CONSTRAINT `fk_entries.category_id__category.id`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE;
  ");

  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseCreate();

?>
