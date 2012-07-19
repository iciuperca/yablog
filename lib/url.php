<?php

/**
 * Handles url tasks.
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */

require_once SYSTEMFOLDER . '/config.php';

class Url {

    static function redirect($url, $perm = false) {
        if ($perm) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        header('Location: ' . $url);
    }

}

?>
