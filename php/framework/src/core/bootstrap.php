<?php
/**
 * User: travi
 * Date: Jan 8, 2011
 * Time: 10:08:35 AM
 */

require_once(dirname(__FILE__).'/../../objects/page/xhtml.class.php');
require_once(dirname(__FILE__).'/../http/Request.class.php');
require_once(dirname(__FILE__).'/../http/Response.class.php');
require_once(dirname(__FILE__).'/../controller/front/front.controller.php');

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    dirname(__FILE__).'/../../../thirdparty/PHP-Dependency/library/'
);

function __autoload($class_name) {
    require_once(str_replace('_', '/', $class_name) . '.php');
}

//Initialize Dependency Injection Container
$container = Pd_Container::get();

//Get Config
//Temp definition
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define('SITE_ROOT', DOC_ROOT.'../');
require_once(dirname(__FILE__).'/../../../thirdparty/spyc/spyc.php');

$config = Spyc::YAMLLoad(SITE_ROOT.'config/siteConfig.yaml');
$config['debug'] = true;
$config['docRoot'] = DOC_ROOT;

//Add Dependencies
$container->dependencies()->set('config', $config);
$container->dependencies()->set('request', new Request());
$container->dependencies()->set('response', new Response($config));

//Handle request

/** @var $frontController FrontController */
$frontController = Pd_Make::name('FrontController');
$frontController->processRequest();
