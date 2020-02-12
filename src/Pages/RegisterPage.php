<?php

namespace WTSA1\Pages;
use WTSA1\Engines\Session;
use WTSA1\Models\User;

class RegisterPage extends Page {
    protected $template = 'Register.phtml';

    protected function get() {}

    protected function post() {
        if (empty($_REQUEST['username'])) {
            $this->data['error'] = "Bitte gib ein Username ein!";
            return;
        }

        if (empty($_REQUEST['password'])) {
            $this->data['error'] = "Bitte gib ein Passwort ein!";
            return;
        }

        if ($_REQUEST['password'] != $_REQUEST['passwordrep']) {
            $this->data['error'] = "Die Passwörter stimmen nicht überein!";
            return;
        }

        if (User::getByName(trim($_REQUEST['username'])) != null) {
            $this->data['error'] = "Der angegebene Username wird bereits verwendet!";
            return;
        }

        $user = User::register($_REQUEST['username'], $_REQUEST['password']);
        if (!$user) {
            $this->data['error'] = "Ein unbekannter Fehler ist bei der Registrierung aufgetaucht.";
            return;
        }

        Session::getInstance()->setUser($user);
        header("Location: /");
    }
}

?>
