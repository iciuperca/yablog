<?php
/**
 * Config class parses and returns the main config file of the app.
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */
class Config {

    /**
     * @var mixed The array containing the config data
     */
    private $config_array;

    /**
     * Takes a string with the .ini file to be parsed
     * @param string $filename The name of the ini file to be prsed
     */
    public function __construct($filename = CONFIGFILE) {
            $this->config_array = parse_ini_file($filename, true);
    }

    /**
     * Returns an array of teh config items.
     *
     * @return mixed Array containing the config items
     */
    public function get() {
        return $this->config_array;
    }

    /**
     * Gets a specific section from the config file
     *
     * @param string $sectionname
     * @return mixed
     * @throws Exception
     */
    public function getSection($sectionname) {
        if(!array_key_exists($sectionname, $this->config_array)) {
            throw new Exception("Unkown section " . $sectionname);
        }else {
            return $this->config_array[$sectionname];
        }
    }

}
