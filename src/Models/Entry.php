<?php

namespace WTSA1\Models;
use WTSA1\Engines\Database;
use WTSA1\Engines\Session;
use WTSA1\Models\User;
use WTSA1\Models\Category;

class Entry {
    private $_id;
    private $_user_id;
    private $_category_id;
    private $_publish_date;
    private $_content;
    private $_image;

    // Getters
    public function getId() { return $this->_id; }
    public function getUserId() { return $this->_user_id; }
    public function getUser() { return User::getById($this->getUserId()); }
    public function getCategoryId() { return $this->_category_id; }
    public function getCategory() { return Category::getById($this->getCategoryId()); }
    public function getPublishDate() { return $this->_publish_date; }
    public function getContent() { return $this->_content; }
    public function getImage() { return $this->_image; }

    public function getFormattedPublishDate() {
        return date('d.m.Y', strtotime($this->_publish_date));
    }

    public function getEncodedImage() {
        if (empty($this->_image)) return "";
        $fileInfo = getimagesizefromstring($this->_image);
        return "data:".$fileInfo["mime"].";base64,".base64_encode($this->_image);
    }

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
    public function setImage($image) {
        $this->_image = $image;
    }

    // Functions

    public function __construct(
        $id = null,
        $user_id = null,
        $category_id = null,
        $publish_date = null,
        $content = null,
        $image = null
    ) {
        $this->_id = $id;
        $this->_user_id = $user_id;
        $this->_category_id = $category_id;
        $this->_publish_date = $publish_date;
        $this->_content = $content;
        $this->_image = $image;
    }

    /**
     * List all diary entries for the current user
     * @param array List of fields to be filtered by
     * @return array(Entry) List of diary entries
     */
    public static function getEntriesForCurrentUser($filter_by = array()) {
        if (Session::getInstance()->getUser() == null) return array();

        $filter_by_condition = "";
        if (count($filter_by) > 0) {
            /*
             * Only allow certain fields to be filtered
             */ 
            $filter_by = array_filter($filter_by, function($key) {
                return $key == 'category_id' || $key == 'publish_date';
            }, ARRAY_FILTER_USE_KEY);

            /*
             * Validate data of filters
             */ 
            if (!empty($filter_by['category_id'])) {
                if (is_int($filter_by['category_id']) == false || $filter_by['category_id'] < 1) {
                    unset($filter_by['category_id']);
                } 
            }
            if (!empty($filter_by['publish_date'])) {
                if (is_array($filter_by['publish_date']) == false) {
                    unset($filter_by['publish_date']);
                } else {
                    $publish_date_disabled = false;
                    foreach ($filter_by['publish_date'] as $date) {
                        $date_regex = "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/";
                        if (preg_match($date_regex, $date) == false || empty($date)) {
                            unset($filter_by['publish_date']);
                            $publish_date_disabled = true;
                            break;
                        } 
                    }
                    if (!$publish_date_disabled) {
                        $publish_date_group = array();
                        if (!empty($filter_by['publish_date'][0])) {
                            $from_date = $filter_by['publish_date'][0];
                            $publish_date_group[] = "publish_date >= '$from_date'";
                        }
                        if (!empty($filter_by['publish_date'][1])) {
                            $until_date = $filter_by['publish_date'][1];
                            $publish_date_group[] = "publish_date <= '$until_date'";
                        }
                        $filter_by['publish_date'] = "(" . implode(" AND ", $publish_date_group) . ")";
                    }
                }
            }

            /*
             * Build filter by string
             */ 
            foreach ($filter_by as $key => $value) {
                if ($key == 'publish_date') {
                    $filter_by_condition .= "AND $value ";
                } else if ($key == 'category_id') {
                    $filter_by_condition .= "AND $key = $value ";
                }
            }
        }

        $user_id = Session::getInstance()->getUser()->getId();
        $query = "
            SELECT * FROM `entries` WHERE user_id = ?
            $filter_by_condition
            ORDER BY `publish_date` DESC;
        ";
        $result = Database::getInstance()->query($query, array($user_id));

        if (!is_array($result)) return array();
        return array_map(function($item) {
            return Entry::parse(array($item));
        }, $result);
    }

    /**
     * Get a diary entry object from the database by its id
     * 
     * @param int $id The id of the diary entry to search for
     * @return Entry|null The diary entry object or null if not found
     */
    public static function getById($id)
    {
        $result = Database::getInstance()->query("
                SELECT * FROM `entries` WHERE id = ?
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
     * @param string $image The binary blob of the attached image
     * @return bool
     */
    public static function create($user_id, $category_id, $publish_date, $content, $image)
    {
        try {
            $result = Database::getInstance()->query("
                INSERT INTO entries (user_id, category_id, publish_date, content, image)
                VALUES (?, ?, ?, ?, ?)
            ", array($user_id, $category_id, $publish_date, $content, $image));
        } catch (PDOException $e) {
            return false;
        }
        return true;
    }

    private static function parse($result) {
        if (count($result) > 0) {
            $obj = $result[0];
            $diary = new Entry(
                $obj['id'], 
                $obj['user_id'], 
                $obj['category_id'], 
                $obj['publish_date'], 
                $obj['content'],
                $obj['image']
            );
            return $diary;
        }
        return null;
    }
}

?>