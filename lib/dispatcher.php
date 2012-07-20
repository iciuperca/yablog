<?php

/**
 * Takes an uri string and calls the propper controller and action
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */
require_once SYSTEMFOLDER . '/config.php';
require_once SYSTEMFOLDER . '/url.php';

class Dispatcher {

    /**
     * @var string The url to be processed
     */
    private $url;

    /**
     * @var Router Instance of Router class
     */
    private $router;

    /**
     * @var Config Instance of the config class
     */
    private $config;

    public function __construct($url) {
        $this->setUrl($url);
        $this->router = new Router();
        $this->config = new Config();

        //TODO: Move this to a config file somewhere
        $this->router->addRoute('alpha:controller/alpha:action/alphanum:slug');
        $this->router->addRoute('alpha:controller/alpha:action');
    }

    /**
     * Setter method for the url.
     *
     * @param string $url The uri string to be processed
     */
    public function setUrl($url) {
        $this->url = $url;
    }

    /**
     * Processs the url and dispatches te correct controller/action
     * @param type $url
     */
    public function dispatch($url = null) {
        if (is_null($url)) {//if the url is not set then set it to the one set in the constructor
            $url = $this->url;
        }
        //get the route from the router
        $route = $this->router->getRoute($url);
        //get the url section of the config
        $url_conf = $this->config->getSection('url');

        if ($url == '/') {//if this is the root url redirect to the dfault controller/action
            Url::redirect($url_conf['default_url']);
        }
        elseif ($route === false) {//if no route is matched show 404
            $this->show_404();
        }

        //if the controller matched does not exist show 404
        if (!file_exists(CONTROLLERS . '/' . $route['controller'] . '.php')) {
            $this->show_404();
        } else {
            require_once CONTROLLERS . '/' . $route['controller'] . '.php';
            $controller = new $route['controller']();
            $action = $route['action'] . 'Action';

            //if the controller does not have the action matched show 404
            if (!method_exists($controller, $action)) {
                $this->show_404();
            } else {
                //everithing is fine, call the controller/method
                if (isset($route['slug'])) {
                    $controller->$action($route['slug']);
                } else {
                    $controller->$action();
                }
            }
        }
    }

    /**
     * Sends a 404 header
     */
    public function show_404() {
        Url::show_404();
    }

}

?>
