<?php

namespace WTSA1\Pages;
use WTSA1\Middlewares\AuthenticationMiddleware;
use WTSA1\Models\Entry;

class HomePage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'Home.phtml';

    protected function get() {
        $this->data['entries'] = Entry::getEntriesForCurrentUser();
    }
}

?>