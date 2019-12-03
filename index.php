<?php
  
  // Use same namespace as sources autoloaded from `./src`.
  // This way, we don't need to prefix all invocations!
  namespace WTSA1;
  
  // Autoload source and dependencies via composer
  require_once __DIR__ . '/vendor/autoload.php';

  // Hello world!
  print((new Greeter())->greet());
?>
