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

  echo "Dropping foreign key relationship between diary_entry.user_id and user.id...\r\n";
  try {
    Database::getInstance()->query("
    ALTER TABLE `diary_entry` DROP FOREIGN KEY `fk_diary_entry.user_id__user.id`
  ");
  } catch (PDOException $e) {}

  echo "Dropping foreign key relationship between diary_entry.category_id and category.id...\r\n";
  try {
    Database::getInstance()->query("
      ALTER TABLE `diary_entry` DROP FOREIGN KEY `fk_diary_entry.category_id__category.id`
    ");
  } catch (PDOException $e) {}
  
  echo "Dropping `user`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `user`
  ");

  echo "Dropping `category`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `category`
  ");

  echo "Dropping `diary_entry`...\r\n";
  Database::getInstance()->query("
    DROP TABLE IF EXISTS `diary_entry`
  ");

  echo "Done!\r\n";
}

// Automatically run script if executed via runscript
if (getenv('AUTORUN_SCRIPT')) databaseDrop();

?>
