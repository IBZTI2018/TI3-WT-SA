<?php

namespace WTSA1\Pages;
use WTSA1\Middlewares\AuthenticationMiddleware;
use WTSA1\Models\Entry;

class HomePage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'Home.phtml';

    protected function get() {
        $filter_by = array();
        if (isset($_REQUEST['filter_by_category_id']) && empty($_REQUEST['filter_by_category_id']) == false) {
            $filter_by['category_id'] = intval($_REQUEST['filter_by_category_id']);
        }
        if (isset($_REQUEST['filter_by_from_date']) && empty($_REQUEST['filter_by_from_date']) == false) {
            if (isset($filter_by['publish_date']) == false) {
                $filter_by['publish_date'] = array();    
            }
            $filter_by['publish_date'][0] = $_REQUEST['filter_by_from_date'];
        }

        if (isset($_REQUEST['filter_by_until_date']) && empty($_REQUEST['filter_by_until_date']) == false) {
            if (isset($filter_by['publish_date']) == false) {
                $filter_by['publish_date'] = array();    
            }
            $filter_by['publish_date'][1] = $_REQUEST['filter_by_until_date'];
        }
        $this->data['entries'] = Entry::getEntriesForCurrentUser($filter_by);
    }
}

?>