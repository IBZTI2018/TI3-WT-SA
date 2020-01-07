<?php

namespace WTSA1\Models;
use WTSA1\Engines\Database;
use WTSA1\Models\User;
use WTSA1\Models\Category;

class DiaryEntry {
    private $_id;
    private $_user_id;
    private $_category_id;
    private $_publish_date;
    private $_content;

    // Getters
    public function getId() { return $this->_id; }
    public function getUserId() { return $this->_user_id; }
    public function getUser() { return User::getById($this->getUserId()); }
    public function getCategoryId() { return $this->_category_id; }
    public function getCategory() { return Category::getById($this->getCategoryId()); }
    public function getPublishDate() { return $this->_publish_date; }
    public function getContent() { return $this->_content; }

    // Setters
    public function setId($id) { 
        $this->_id = $id; 
    }
    public function setUserId($user_id) { 
        $this->_user_id = $user_id; 
    }
    public function setCategoryId($category_id) {
        $this->_category_id = $category_id;
    }
    public function setPublishDate($publish_date) {
        $this->_publish_date = $publish_date;
    }
    public function setContent($content) {
        $this->_content = $content;
    }

    // Functions

    public function __construct(
        $id = null,
        $user_id = null,
        $category_id = null,
        $publish_date = null,
        $content = null
    ) {
        $this->_id = $id;
        $this->_user_id = $user_id;
        $this->_category_id = $category_id;
        $this->_publish_date = $publish_date;
        $this->_content = $content;
    }

    /**
     * Get a diary entry object from the database by its id
     * 
     * @param int $id The id of the diary entry to search for
     * @return DiaryEntry|null The diary entry object or null if not found
     */
    public static function getById($id)
    {
        $result = Database::getInstance()->query("
                SELECT * FROM `diary_entry` WHERE id = ?
            ",
            array($id)
        );
        return self::parse($result);
    }

    /**
     * Create a new diary entry object on the database
     * 
     * @param int $user_id The id of the user
     * @param int $category_id The id of the Category
     * @param string $publish_date The publish date under the format `Y-m-d`, ex: 2019-01-31
     * @param string $content The content of the diary entry
     * @return bool
     */
    public static function create($user_id, $category_id, $publish_date, $content)
    {
        try {
            $result = Database::getInstance()->query("
                    INSERT INTO diary_entry (user_id, category_id, publish_date, content) VALUES (?, ?, ?, ?)
                ",
                array($user_id, $category_id, $publish_date, $content)
            );
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    private static function parse($result) {
        if (count($result) > 0) {
            $obj = $result[0];
            $diary = new DiaryEntry($obj['id'], $obj['user_id'], $obj['category_id'], $obj['publish_date'], $obj['content']);
            return $diary;
        }
        return null;
    }
}

?>