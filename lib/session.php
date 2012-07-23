<?php
/**
 * Thin wrapper arround php session
 *
 * @author Ionut Cristian Ciuperca
 */
class Session {

    /**
     * @var Session instance of the session ($this) class
     */
    private static $instance = null;

    /**
     * Private construc function (singleton)
     *
     * @access private
     */
    private function __construct() {
    }

    /**
     * Return an intance of the class (singleton)
     *
     * @return Session An instance of the class
     */
    public static function getInstance() {
        if(!(self::$instance instanceof Session)) {
            self::$instance = new Session();
        }

        return self::$instance;
    }

    /**
     * Starts a new session
     *
     * @return boolean success status
     */
    public function start() {
        if(session_id() === '') {
            return false;//session already exists
        }else {
            return session_start();
        }
    }

    /**
     * Writes the remaining data and closes the session
     */
    public function close() {
        session_write_close();
    }

    /**
     * Sets a given item to session
     *
     * @param string $item_name The name of the item to be written to session
     * @param mixed $item The item to be written to session
     */
    public function setItem($item_name, $item) {
        $_SESSION[$item_name] = $item;
    }

    /**
     * Gets the item strored under the speciffied key.
     *
     * @param string $item_name
     * @return mixed
     * @throws Exception if the item is not set
     */
    public function getItem($item_name) {
        if(!isset($_SESSION[$item_name])) {
            throw new Exception("Item {$item_name} does not exist.");
        } else {
            return $_SESSION[$item_name];
        }
    }

    /**
     * Checks if a given key exists in the session.
     *
     * @param string $item_name
     * @return bool
     */
    public function itemExists($item_name) {
        return isset($_SESSION[$item_name]);
    }
}