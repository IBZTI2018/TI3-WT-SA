<?php

  // Autoload source and dependencies via composer
  require_once __DIR__ . '/vendor/autoload.php';

  define('APP_DIR', __DIR__);
  define('TEMPLATE_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates');

  // Global helper function for templating
  function t($variable) {
    return (isset($variable)) ? $variable : "";
  }

  // Start application
  (new \WTSA1\Diary())->startApp();
?>
