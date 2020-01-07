<?php

  // Autoload source and dependencies via composer
  require_once __DIR__ . '/vendor/autoload.php';

  define('APP_DIR', __DIR__);
  define('TEMPLATE_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates');

  // Global helper function for templating a variable
  function t($variable) {
    return (isset($variable)) ? $variable : "";
  } 
  
  // Global helper function for templating an array key
  function ta($array, $key) {
    return (array_key_exists($key, $array)) ? $array[$key] : "";
  }

  // Start application
  (new \WTSA1\Diary())->startApp();
?>
