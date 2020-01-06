<?php

namespace WTSA1\Pages;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class LoginPage extends Page {
    protected $template = 'Login.phtml';

    protected function get() {}

    protected function post() {
        if (empty($_REQUEST['username'])) {
            $this->data['error'] = "You must provide a username!";
            return;
        }

        if (empty($_REQUEST['password'])) {
            $this->data['error'] = "You must provide a password!";
            return;
        }

        $user = User::login($_REQUEST['username'], $_REQUEST['password']);
        if (!$user) {
            $this->data['error'] = "Login failed, please try again.";
            return;
        }

        Session::getInstance()->setUser($user);

        // Login successful
        header("Location: /");
    }
}

?>
