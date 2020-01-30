<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Hasher\PBKDF2;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\User;
use WTSA1\Models\Entry;

class EntryModelTest extends TestCase {

    public function setUp(): void {
        Database::getInstance()->query("
          INSERT INTO `users` (id, username, password) VALUES
            (1, 'someuser', '".PBKDF2::generate("password")."');
        ");
        Database::getInstance()->query("
          INSERT INTO `categories` (id, category) VALUES
            (1, 'uncategorized');
        ");
    }

    public function testDoesNotListEntriesForOtherUser() {
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content');
      ");

      Session::getInstance()->setUser(User::login("someuser", "password"));
      $entries = Entry::getEntriesForCurrentUser();

      $this->assertEquals($entries, array());
    }

    public function testDoesListAllEntriesForCurrentUser() {
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content'),
          (2, 1, '2020-01-01', 'some content'),
          (1, 1, '2020-01-01', 'some other content');
      ");

      Session::getInstance()->setUser(User::login("someotheruser", "password"));
      $entries = Entry::getEntriesForCurrentUser();

      $this->assertEquals(count($entries), 2);
    }

    public function testForeignKeyViolationUserIdOnEntry() {
        $this->expectException(PDOException::class);
        Database::getInstance()->query("
          INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (9999999, 1, '2020-01-01', 'constraint');
        ");
    }
    
    public function testForeignKeyAcceptanceUserIdOnEntry() {
        Database::getInstance()->query("
          INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (1, 1, '2020-01-01', 'constraint');
        ");
        $this->assertNull(null);
    } 
    
    public function testForeignKeyViolationCategoryIdOnEntry() {
        $this->expectException(PDOException::class);
        Database::getInstance()->query("
            INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (1, 9999999, '2020-01-01', 'constraint');
        ");
    }
    
    public function testForeignKeyAcceptanceCategoryIdOnEntry() {
        Database::getInstance()->query("
            INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
            (1, 1, '2020-01-01', 'constraint');
        ");
        $this->assertNull(null);
    }

    public function testImageInfoIsEncodedCorrectlyWithMimeType() {
      // NOTE: This assumes tests are always run in the docker container as intended
      //       The model must be used here or any `'` in the blob must be escaped.
      $imageBlob = file_get_contents("/var/www/html/tests/fixtures/image.png");
      Entry::create(1, 1, '2020-01-01', 'content', $imageBlob);

      Session::getInstance()->setUser(User::login("someuser", "password"));
      $entries = Entry::getEntriesForCurrentUser();
      $formatted = $entries[0]->getEncodedImage();

      $this->assertEquals($formatted, "data:image/png;base64,".base64_encode($imageBlob));
    }

    public function testGetEntriesFilterByDate() {
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content'),
          (2, 1, '2019-01-01', 'some content'),
          (2, 1, '2018-01-01', 'some other content');
      ");

      Session::getInstance()->setUser(User::login("someotheruser", "password"));
      $entries = Entry::getEntriesForCurrentUser(array(
          'publish_date' => array(
            '2017-12-31',
            '2019-01-02'
          )
        )
      );

      $this->assertEquals(count($entries), 2);
    }

    public function testGetEntriesFilterByCategory() {
      Database::getInstance()->query("
        INSERT INTO `categories` (id, category) VALUES
          (2, 'test2'),
          (3, 'test3');
      ");
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content'),
          (2, 2, '2019-01-01', 'some content'),
          (2, 3, '2018-01-01', 'some other content');
      ");

      Session::getInstance()->setUser(User::login("someotheruser", "password"));
      $entries = Entry::getEntriesForCurrentUser(array(
          'category_id' => 3
        )
      );

      $this->assertEquals(count($entries), 1);
    }

    public function testGetEntriesFilterByCategoryAndPublishDate() {
      Database::getInstance()->query("
        INSERT INTO `categories` (id, category) VALUES
          (2, 'test2'),
          (3, 'test3');
      ");
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content'),
          (2, 2, '2019-01-01', 'some content'),
          (2, 3, '2018-01-01', 'some other content');
      ");

      Session::getInstance()->setUser(User::login("someotheruser", "password"));
      $entries = Entry::getEntriesForCurrentUser(array(
          'publish_date' => array(
            '2017-12-31',
            '2019-01-02'
          ),
          'category_id' => 1
        )
      );

      $this->assertEquals(count($entries), 0);
    }

    public function testGetEntriesFilterByWrongFilters() {
      Database::getInstance()->query("
        INSERT INTO `categories` (id, category) VALUES
          (2, 'test2'),
          (3, 'test3');
      ");
      Database::getInstance()->query("
        INSERT INTO `users` (id, username, password) VALUES
          (2, 'someotheruser', '".PBKDF2::generate("password")."');
      ");
      Database::getInstance()->query("
        INSERT INTO `entries` (user_id, category_id, publish_date, content) VALUES
          (2, 1, '2020-01-01', 'some content'),
          (2, 2, '2019-01-01', 'some content'),
          (2, 3, '2018-01-01', 'some other content');
      ");

      Session::getInstance()->setUser(User::login("someotheruser", "password"));
      $entries = Entry::getEntriesForCurrentUser(array(
          'publish_date' => array(
            '2017-12',
            '13. Januar 2008'
          ),
          'category_id' => 'Ausflug'
        )
      );

      $this->assertEquals(count($entries), 3);
    }
}
?>
