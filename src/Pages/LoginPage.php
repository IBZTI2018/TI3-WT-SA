<?php

namespace WTSA1\Pages;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class LoginPage extends Page {
    protected $template = 'Login.phtml';

    protected function get() {}

    protected function post() {
        if (!isset($_REQUEST['username']) || strlen($_REQUEST['username']) < 1) {
            $this->data['error'] = "Der Username ist leer!";
            return;
        }

        if (!isset($_REQUEST['password']) || strlen($_REQUEST['password']) < 1) {
            $this->data['error'] = "Das Passwort ist leer!";
            return;
        }

        $user = User::login($_REQUEST['username'], $_REQUEST['password']);
        if (!$user) {
            $this->data['error'] = "Der Username oder das Passwort ist falsch!";
            return;
        }

        Session::getInstance()->setUser($user);

        // Login successful
        header("Location: /");
    }
}

?>