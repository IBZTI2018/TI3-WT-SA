<?php

namespace WTSA1\Pages;

use WTSA1\Engines\Session;
use WTSA1\Middlewares\AuthenticationMiddleware;
use WTSA1\Models\DiaryEntry;

class CreatePage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'Create.phtml';

    protected function get() {}

    protected function post() {
        if (empty($_REQUEST['publish_date'])) {
            $this->data['error'] = "Du musst ein Veröffentlichungsdatum angeben!";
            return;
        }

        if (empty($_REQUEST['category'])) {
            $this->data['error'] = "Du musst eine Kategorie auswählen!";
            return;
        }

        if (empty($_REQUEST['content'])) {
            $this->data['error'] = "Du musst ein Inhalt schreiben!";
            return;
        }

        if (strlen($_REQUEST['content']) > 1000) {
            $this->data['error'] = "Dein Inhalt darf nicht mehr als 1000 Charaktere betragen!";
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