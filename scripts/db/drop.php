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

  echo "Dropping foreign key relationship between entries.user_id and user.id...\r\n";
  try {
    Database::getInstance()->query("
    ALTER TABLE `entries` DROP FOREIGN KEY `fk_entries.user_id__user.id`
  ");
  } catch (PDOException $e) {}

  echo "Dropping foreign key relationship between entries.category_id and category.id...\r\n";
  try {
    Database::getInstance()->query("
      ALTER TABLE `entries` DROP FOREIGN KEY `fk_entries.category_id__category.id`
    ");
  } catch (PDOException $e) {}
  
  echo "Dropping `users`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `users`
  ");

  echo "Dropping `categories`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `categories`
  ");

  echo "Dropping `entries`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `entries`
  ");

  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseDrop();

?>
