<?php

$config = array();
session_start();
if (isset($_SESSION['debug'])) {
    $config['debug'] = true;
}


// date_default_timezone_set('America/Chicago');	//TODO: pull from config file

define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
$levels = preg_split("/\//", DOC_ROOT);
array_pop($levels);
array_pop($levels);
define('SITE_ROOT', join($levels, "/")."/");
array_pop($levels);
define('WEB_ROOT', join($levels, "/")."/");
define('FRAMEWORK_PATH', dirname(__FILE__).'/');

require_once SITE_ROOT.'config/framework/framework.conf';