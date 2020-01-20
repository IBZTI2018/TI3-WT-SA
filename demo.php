<?php

  // Create inspect function
  function inspect($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
  }

  // Establish database connection
  define('MYSQL_HOST', 'mysql');
  define('MYSQL_NAME', 'project');
  define('MYSQL_USER', 'admin');
  define('MYSQL_PASS', 'password');

  try {
    $conn = new PDO('mysql:host='.MYSQL_HOST.';dbname='.MYSQL_NAME, MYSQL_USER, MYSQL_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }


  // // Select all users from database and list them
  // $stmt = $conn->query('SELECT * FROM user');
  // while ($row = $stmt->fetch()) {
  //   inspect($row);
  // }
  

  // // Select all users from database and list them
  // // using a custom fetch mode for associative-array
  // $stmt = $conn->query('SELECT * FROM user');
  // $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // while ($row = $stmt->fetch()) {
  //   inspect($row);
  // }


  // // Select a specific user from the database using an
  // // input given by a user (e.g. login form)
  // $loginUsername = 'sgehring';
  // $stmt = $conn->query('SELECT * FROM user WHERE username = "'.$loginUsername.'";');
  // $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // while ($row = $stmt->fetch()) {
  //   inspect($row);
  // }


  // // An evil user using an SQL injection for getting the login
  // // information of all users in the database.
  // $loginUsername = 'sgehring" OR 1=1; --';
  // $stmt = $conn->query('SELECT * FROM user WHERE username = "'.$loginUsername.'";');
  // $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // while ($row = $stmt->fetch()) {
  //   inspect($row);
  // }


  // // Preventing attacks by escaping strings (as used in mysqli)
  // // This code does not run within this demonstration!!
  // $loginUsername = 'sgehring" OR 1=1; --';
  // $safeLoginUsername = mysqli_real_escape_string($loginUsername);
  // $stmt = $conn->query('SELECT * FROM user WHERE username = "'.$safeLoginUsername.'";');
  // $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // while ($row = $stmt->fetch()) {
  //   inspect($row);
  // }


  // // Using prepared PDO statements for sanitizing input
  // // $loginUsername = 'sgehring" OR 1=1; --';
  // $loginUsername = 'sgehring';//" OR 1=1; --';
  // $stmt = $conn->prepare('SELECT * FROM user WHERE username = :name;');
  // $stmt->execute([':name' => $loginUsername]);
  // $stmt->setFetchMode(PDO::FETCH_ASSOC);
  // while ($row = $stmt->fetch()) {
  //   inspect($row);
  // }


  // Close database connection
  $conn = null;

?>
