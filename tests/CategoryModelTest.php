<?php

require_once __DIR__ . '/_CustomTestRunnerHooks.php';
use PHPUnit\Framework\TestCase;

use WTSA1\Engines\Database;
use WTSA1\Models\Category;

class CategoryModelTest extends TestCase {

    public function testReturnsNullIfCategoryNotFoundById() {
        $category = Category::getById(1);
        $this->assertNull($category);
    }

    public function testReturnsCategoryIfFoundById() {
        Database::getInstance()->query("
          INSERT INTO `category` (id, category)
          VALUES (44, 'test');
        ");

        $category = Category::getById(44);
        $this->assertNotNull($category);
        $this->assertEquals(get_class($category), "WTSA1\Models\Category");
    }
}
?>