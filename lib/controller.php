<?php
class Controler {

    public function construct() {

    }

    /**
     * Renders a template.
     *
     * @param string $template The path to the template file.
     * @param mixed $data An array of variables to be passed to the template
     */
    public function render($template, $data) {
        extract($data);
        ob_start();
        require_once $template;
        $_content = ob_get_clean();
        include $_extends;
    }

    /**
     * Sends a 404 header
     */
    public function show_404() {
        Url::show_404();
    }
}