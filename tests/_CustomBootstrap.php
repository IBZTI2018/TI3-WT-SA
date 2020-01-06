<?php

  require_once "./vendor/autoload.php";

  // It is required to start a session before PHPUnit even
  // starts, otherwise it will fail, because it assumes that
  // the headers have already been sent to the client (which
  // obviously does not matter when testing)
  session_start();

?>
