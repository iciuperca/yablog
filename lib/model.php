<?php

/**
 * Class to be extended by the app's models
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */
class Model extends PDO {

    public function __construct() {
        require_once SYSTEMFOLDER . '/config.php';
        $conf = new Config();
        $dbconfig = $conf->getSection('database');
        $dsn = "mysql:host={$dbconfig['host']};dbname={$dbconfig['database']}";
        parent::__construct($dsn, $dbconfig['user'], $dbconfig['password']);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Get all of the result matching the specified query
     *
     * @param string $sql The sql query
     * @param mixed $params The parameters for the query
     * @return array
     */
    public function getAssocArray($sql, $params = array()) {
        try {
            $stmt = $this->prepare($sql);
            $params = is_array($params) ? $params : array($params);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Return  single rpw matching the specified query
     * 
     * @param string $sql The sql query
     * @param mixed $params The parametters for the query
     * @return array Containing the matching row
     */
    public function getAssocRow($sql, $params = array()) {
        $res = $this->getAssocArray($sql, $params);

        if (empty($res)) {
            return array();
        } else {
            return $res[0];
        }
    }

}
