<?php

namespace WTSA1\Pages;

use WTSA1\Engines\Session;
use WTSA1\Middlewares\AuthenticationMiddleware;
use WTSA1\Models\DiaryEntry;

class DiaryEntryPage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'DiaryEntry.phtml';

    protected function get() {}

    protected function post() {
        if (empty($_REQUEST['publish_date'])) {
            $this->data['error'] = "You must specify a publish date!";
            return;
        }

        if (empty($_REQUEST['category'])) {
            $this->data['error'] = "You must choose a category!";
            return;
        }

        if (empty($_REQUEST['content'])) {
            $this->data['error'] = "You must write some content!";
            return;
        }

        if (strlen($_REQUEST['content']) > 1000) {
            $this->data['error'] = "Your content can't have more than 1000 characters!";
            return;
        }

        $user_id = Session::getInstance()->getUser()->getId();
        $category_id = $_REQUEST['category'];
        $publish_date = $_REQUEST['publish_date'];
        $content = $_REQUEST['content'];

        DiaryEntry::create($user_id, $category_id, $publish_date, $content);

        if (defined("PHPUNIT_WTSA1_TESTSUITE") != true) {
            header("Location: /");
        }

    }

}

?>