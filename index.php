<?php
  
  // Use same namespace as sources autoloaded from `./src`.
  // This way, we don't need to prefix all invocations!
  namespace WTSA1;
  
  // Autoload source and dependencies via composer
  require_once __DIR__ . '/vendor/autoload.php';

  define('APP_DIR', __DIR__);
  define('TEMPLATE_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Templates');

  // Start application
  (new Diary())->startApp();
?>
