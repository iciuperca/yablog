<?php

// define system constants

define ('APPFOLDER', 'app');
define ('SYSTEMFOLDER', 'lib');
define ('CONTROLLERS', APPFOLDER . '/controllers');
define ('MODELS', APPFOLDER . '/models');
define ('TEMPLATES', APPFOLDER . '/templates');
define ('CONFIGFOLDER', APPFOLDER . '/config');
define ('CONFIGFILE', CONFIGFOLDER . '/config.ini');



require_once SYSTEMFOLDER . '/router.php';
require_once SYSTEMFOLDER . '/dispatcher.php';


$uri = $_SERVER['REQUEST_URI'];
$dispatcher = new Dispatcher($uri);
$dispatcher->dispatch();