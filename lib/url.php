<?php

/**
 * Handles url tasks.
 *
 * @author Ionut Cristian Ciuperca <ionut.ciuperca@gmail.com>
 */

require_once SYSTEMFOLDER . '/config.php';

class Url {

    /**
     * Redirects to a given url
     *
     * @param string $url The url to which the redirection will be made
     * @param bool $perm Sets if the permanent header will be sent.
     */
    static function redirect($url, $perm = false) {
        if ($perm) {
            header('HTTP/1.1 301 Moved Permanently');
        }
        header('Location: ' . $url);
        exit();
    }

    /**
     * Sends a 404 header and displayes a message
     * @param string $message The optional message to be displayed.
     */
    static function show_404($message = '404 Page not found') {
        header('HTTP/1.1 301 Moved Permanently');
        echo $message;
        exit();
    }

}

?>
