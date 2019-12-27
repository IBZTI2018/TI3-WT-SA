<?php

namespace WTSA1\Pages;

class Page {
    /**
     * Template being rendered.
     */
    protected $template = null;
    protected $data = array();


    /**
     * Initialize a new view context.
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
     * @return string The rendered template.
     */
    public function render() {
        extract($this->data);

        ob_start();
        include( TEMPLATE_DIR . DIRECTORY_SEPARATOR . $this->template);
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }
}

?>