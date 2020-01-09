<?php

namespace WTSA1\Pages;

use \WTSA1\Engines\Session;

class Page {
    /**
     * Template being rendered.
     */
    protected $template = null;
    protected $data = array();


    /**
     * Initialize a new page context.
     */
    public function __construct($template = null) {
        if ($template != null) {
            $this->template = $template;
        }

        if (strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
            $this->get();
        }
        else if (strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
            $this->post();
        }
    }

    protected function get() {}
    protected function post() {}


    /**
     * Render the template, returning it's content.
     * 
     * @param array $bindings Optional bindings. Supports keys: title
     * @return string The rendered template.
     */
    public function render($bindings = array()) {
        extract($this->data);

        // Add binding for internal page if user is logged in
        if (Session::getInstance()->getUser() != null) {
            $bindings["bodyModifier"] = "diary-open";
        }

        ob_start();
        include(TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'Components/Header.phtml');
        include(TEMPLATE_DIR . DIRECTORY_SEPARATOR . $this->template);
        include(TEMPLATE_DIR . DIRECTORY_SEPARATOR . 'Components/Footer.phtml');
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }
}

?>