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
    private static $instance;

    /**
     * Private construct function (singleton)
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
        if((empty(self::$instance))) {
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
        if(session_id() !== '') {
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
     * @return mixed The data in session or null
     */
    public function getItem($item_name) {
        if(!isset($_SESSION[$item_name])) {
            //throw new Exception("Item {$item_name} does not exist.");
            return null;
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

    /**
     * Removes an intem from the session
     *
     * @param string $key The name of utem in the session.
     * @throws Exception If the key is not in the session.
     */
    public function removeItem($key) {
        if($this->itemExists($key)) {
            unset($_SESSION[$key]);
        } else {
            throw new Exception("Key not found in session: {$key}");
        }
    }

    /**
     * Sets ot updates a flash message
     * @param string $key The key for the flash
     * @param mixed $value The value (ussualy string)
     */
    public function setFlash($key, $value) {
        $_SESSION['flash'][$key] = $value;
    }

    /**
     * Gets a flash item stored under a speciffic key
     *
     * @param string $key The key of the flash
     * @param bool $keep If the irem is kept aster returning it. Defaults to false
     * @return mixed The flash item
     * @throws Exception If the key does not exist
     */
    public function getFlash($key, $keep = false) {
        if(!isset($_SESSION['flash'][$key])) {
            throw new Exception('Flash storage has no key named ' . $key);
        }
        $item = $_SESSION['flash'][$key];
        if(!$keep) {
            unset($_SESSION['flash'][$key]);
        }

        return $item;
    }

    /**
     * Checks if the flash array has the given key
     *
     * @param string $key The key to be tested
     * @return bool
     */
    public function hasFlash($key) {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Returns all of the flash items
     *
     * @param bool $keep if the array is kept after returning it
     * @return array
     */
    public function getAllFlash($keep = false) {
        $flashes = $_SESSION['flash'];
        if(!$keep) {
            unset($$_SESSION['flash']);
        }
        return $flashes;
    }
}