<?php

namespace WTSA1\Pages;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class RegisterPage extends Page {
    protected $template = 'Register.phtml';

    protected function get() {}

    protected function post() {
        if (empty($_REQUEST['username'])) {
            $this->data['error'] = "You must specify a username!";
            return;
        }

        if (empty($_REQUEST['password'])) {
            $this->data['error'] = "You must specify a password!";
            return;
        }

        if ($_REQUEST['password'] != $_REQUEST['passwordrep']) {
            $this->data['error'] = "Passwords don't match!";
            return;
        }

        if (User::getByName(trim($_REQUEST['username'])) != null) {
            $this->data['error'] = "Username is already taken!";
            return;
        }

        $user = User::register($_REQUEST['username'], $_REQUEST['password']);
        if (!$user) {
            $this->data['error'] = "An error occured while creating your account";
            return;
        }

        Session::getInstance()->setUser($user);
        header("Location: /");
    }
}

?>
