<?php

namespace WTSA1\Middlewares;

use WTSA1\Engines\Session;

trait AuthenticationMiddleware {
    function __construct()
    {
        /*
         * Redirect user to LoginPage if is not logged in
         */ 
        if (Session::getInstance()->getUser() == null) {
            header('Location: /login');
            die();
        }
    }

    public function render($bindings = array()) {
        $this->data = array_merge($this->data, array(
            "user" => Session::getInstance()->getUser()
        ));
        parent::render();
    }
}

?>