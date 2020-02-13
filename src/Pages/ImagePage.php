<?php

namespace WTSA1\Pages;

use WTSA1\Engines\Session;
use WTSA1\Middlewares\AuthenticationMiddleware;
use WTSA1\Models\Entry;

class ImagePage extends Page {
    use AuthenticationMiddleware;
    protected $template = 'Image.phtml';
    protected $no_layout = true;

    protected function get() {
        $entry_id = !empty($_REQUEST['entry_id']) ? $_REQUEST['entry_id'] : null;
        if (!$entry_id) {
            // Check if entry_id is given as a parameter
            $this->data['error'] = "Beim Laden des Bildes ist ein Fehler aufgetretten.";
        } else {
            $entry = Entry::getById($entry_id);
            if (!$entry) {
                // Check if Entry exists by given entry_id
                $this->data['error'] = "Beim Laden des Bildes ist ein Fehler aufgetretten.";
            } else if ($entry->getUserId() != Session::getInstance()->getUser()->getId()) {
                // Checks if entry object belongs to the session user
                $this->data['error'] = "Beim Laden des Bildes ist ein Fehler aufgetretten.";
            } else {
                $this->data['image'] = $entry->getEncodedImage();
            }
        }
    }

    protected function post() {}

}

?>