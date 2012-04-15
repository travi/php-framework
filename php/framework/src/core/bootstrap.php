<?php
require_once dirname(__FILE__).'/init.php';

require_once dirname(__FILE__) . '/../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../controller/front/front.controller.php';

// Report all errors except E_NOTICE (should try to use this for development)
//error_reporting(E_ALL ^ E_NOTICE);
// Report all errors except E_NOTICE and E_WARNING (better for production)
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
ini_set('display_errors', 'Off');
ini_set('log_errors', 'On');

/** @var $frontController FrontController */
$frontController = Pd_Make::name('FrontController');
$frontController->processRequest();