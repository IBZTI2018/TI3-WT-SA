<?php

namespace WTSA1\Pages;
use WTSA1\Middlewares\AuthenticationMiddleware;
use WTSA1\Models\Entry;

class HomePage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'Home.phtml';

    protected function get() {
        $filter_by = array();
        if (!empty($_REQUEST['filter_by_category_id'])) {
            $filter_by['category_id'] = intval($_REQUEST['filter_by_category_id']);
        }
        
        if (!empty($_REQUEST['filter_by_from_date'])) {
            if (isset($filter_by['publish_date']) == false) {
                $filter_by['publish_date'] = array();    
            }
            $filter_by['publish_date'][0] = $_REQUEST['filter_by_from_date'];
        }

        if (!empty($_REQUEST['filter_by_until_date'])) {
            if (isset($filter_by['publish_date']) == false) {
                $filter_by['publish_date'] = array();    
            }
            $filter_by['publish_date'][1] = $_REQUEST['filter_by_until_date'];
        }
        $this->data['entries'] = Entry::getEntriesForCurrentUser($filter_by);
    }
}

?>