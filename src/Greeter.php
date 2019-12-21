<?php

namespace WTSA1;

class Greeter {
  public function greet() {
    print("Hello World!");
    print(var_dump(Database::getInstance()->query("SHOW DATABASES;")));
  }
}

?>
