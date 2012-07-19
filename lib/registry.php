<?php
/**
 * Implementation of the registry design patern.
 *
 * @author Ionut Ciuperca <ionut.ciuperca@gmail.com>
 */
class Registry {

    /**
     * @var array Contains all of the stored objects
     */
    static private $_stored_data;

    /**
     * Add an object to the registry.
     *
     * @param mixed $object The object to be stored
     * @param string $name The name of the object. if none is given the class name will be used
     */
    static public function add($object, $name = null) {
        $name = !is_null($name) ? strtolower($name) : strtolower(get_class($object));

        self::$_stored_data[$name] = $object;
    }

    /**
     * Gets an object from the registry
     *
     * @param string $name The name of the object
     * @return mixed The object stored at that key
     * @throws Exception
     */
    static public function get($name) {
        if(!self::contains($name)) {
            throw new Exception(sprintf('Unknown object: "%s"', $name));
        }

        return self::$_stored_data[$name];
    }

    /**
     * Checks if an object is stored under that name
     *
     * @param string $name The name of the object
     * @return bool
     */
    static public function contains($name) {
        return array_key_exists($name, self::$_stored_data);
    }

    /**
     * Removes an object from th registry.
     *
     * @param string $name The name of the objects
     * @throws Exception if the name does not exist in the registry.
     */
    static public function remove($name) {
        if(!self::contains($name)) {
            throw new Exception(sprintf('Unknown object: "%s"', $name));
        }

        unset(self::$_stored_data[$name]);
    }
}