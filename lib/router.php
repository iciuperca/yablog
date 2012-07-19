<?php

/**
 * Router class, largely taken from "PHP Master: Write Cutting-edge Code" book, 
 */

class Router {

    const REGEX_ANY = "([^/]+?)";
    const REGEX_INT = "([0-9]+?)";
    const REGEX_ALPHA = "([a-zA-Z_-]+?)";
    const REGEX_ALPHANUMERIC = "([0-9a-zA-Z_-]+?)";
    const REGEX_STATIC = "%s";

    protected $routes = array();
    protected $baseUrl = '';

    public function setBaseUrl($baseUrl) {
        $this->baseUrl = preg_quote($baseUrl, '@');
    }

    public function addRoute($route, $options = array()) {
        $this->routes[] = array('pattern' => $this->_parseRoute($route), 'options' => $options);
    }

    protected function _parseRoute($route) {
        $baseUrl = $this->baseUrl;
        if ($route == '/') {
            return "@^$baseUrl/$@";
        }

        $parts = explode('/', $route);

        $regex = "@^$baseUrl";

        if ($route[0] == '/') {
            array_shift($parts);
        }

        foreach ($parts as $part) {
            $regex .= '/';

            $args = explode(':', $part);

            if (sizeof($args) == 1) {
                $regex .= sprinf(self::REGEX_STATIC, preg_quote(array_shift($args), '@'));
                continue;
            } elseif ($args[0] == '') {
                array_shift($args);
                $type = false;
            } else {
                $type = array_shift($args);
            }

            $key = array_shift($args);

            if ($type == 'regex') {
                $regex .= $key;
                continue;
            }

            $this->normalize($key);

            $regex .= '(?P<' . $key . '>';

            switch (strtolower($type)) {
                case 'int':
                case 'integer':
                    $regex .= self::REGEX_INT;
                    break;
                case 'alpha':
                    $regex .= self::REGEX_ALPHA;
                    break;
                case 'alphanumeric':
                case 'alphanum':
                case 'alnum':
                    $regex .= self::REGEX_ALPHANUMERIC;
                    break;

                default:
                    $regex .= self::REGEX_ANY;
                    break;
            }

            $regex .= ')';
        }
        $regex .= '$@u';

        return $regex;
    }

    public function getRoute($request) {
        $matches = array();

        foreach ($this->routes as $route) {

            if (preg_match($route['pattern'], $request, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_int($key)) {
                        unset($matches[$key]);
                    }
                }
                $result = $matches + $route['options'];
                return $result;
            }
        }

        return false;
    }

    public function normalize(&$param) {
        $param = preg_replace("/[^a-zA-Z0-9]/", "", $param);
    }

}