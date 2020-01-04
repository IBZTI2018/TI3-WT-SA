<?php

namespace WTSA1\Pages;
use WTSA1\Middlewares\AuthenticationMiddleware;

class HomePage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'Home.phtml';
}

?>