<?php
/**
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */

require_once SYSTEMFOLDER.'/session.php';
class Controler {

    public $session;
    public function __construct() {
        $this->session = Session::getInstance();
    }

    /**
     * Renders a template.
     *
     * @param string $template The path to the template file.
     * @param mixed $data An array of variables to be passed to the template
     */
    public function render($template, $data = null) {
        if(!is_null($data)) {
            extract($data);
        }
        $user = $this->session->getItem('user');//TODO: move this from here
        ob_start();
        require_once $template;
        $_content = ob_get_clean();
        include_once $_extends;
    }

    /**
     * Sends a 404 header
     */
    public function show_404() {
        Url::show_404();
    }
}