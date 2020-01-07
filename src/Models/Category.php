<?php

namespace WTSA1\Models;
use WTSA1\Engines\Database;

class Category {
    private $_id;
    private $_category;

    // Getters
    public function getId() { return $this->_id; }
    public function getCategory() { return $this->_category; }

    // Setters
    public function setId($id) { 
        $this->_id = $id; 
    }
    public function setCategory($category) { 
        $this->_category = $category; 
    }

    // Functions

    public function __construct(
        $id = null,
        $category = null
    ) {
        $this->_id = $id;
        $this->_category = $category;
    }

    /**
     * Get a category object from the database by its id
     * 
     * @param int $id The id of the category to search for
     * @return Category|null The user object or null if not found
     */
    public static function getById($id) {
        $result = Database::getInstance()->query("
                SELECT * FROM `category` WHERE id = ?
            ",
            array($id)
        );
        return self::parse($result);
    }

    private static function parse($result) {
        if (count($result) > 0) {
            $obj = $result[0];
            $category = new Category($obj['id'], $obj['category']);
            return $category;
        }
        return null;
    }
}

?>