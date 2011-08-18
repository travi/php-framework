<?php

require_once dirname(__FILE__) . '/../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../http/Request.class.php';
require_once dirname(__FILE__).'/../http/Response.class.php';
require_once dirname(__FILE__).'/../controller/front/front.controller.php';

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    dirname(__FILE__).'/../../../thirdparty/PHP-Dependency/library/'
);

function __autoload($class_name)
{
    include_once str_replace('_', '/', $class_name) . '.php';
}

//Initialize Dependency Injection Container
$container = Pd_Container::get();

//Get Config
//Temp definition
define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define('SITE_ROOT', DOC_ROOT.'../');
require_once dirname(__FILE__).'/../../../thirdparty/spyc/spyc.php';

$config = Spyc::YAMLLoad(SITE_ROOT.'config/siteConfig.yml');
$config['nav'] = Spyc::YAMLLoad(SITE_ROOT.'config/nav.yml');
$config['debug'] = true;         //TODO: make this automated based on environment
$config['docRoot'] = DOC_ROOT;

$uiDeps = Spyc::YAMLLoad(dirname(__FILE__).'/../../../../config/uiDependencies.yaml');
$siteUiDeps = Spyc::YAMLLoad(SITE_ROOT.'config/dependencies/components.yaml');

$config['uiDeps']['widgets'] = $uiDeps;
$config['uiDeps']['siteWidgets'] = $siteUiDeps;
$config['uiDeps']['pages'] = Spyc::YAMLLoad(SITE_ROOT.'config/pageDependencies.yml');

//to make legacy stuff work
$uiDeps = array_merge_recursive($uiDeps, $siteUiDeps);

//Add Dependencies
$container->dependencies()->set('config', $config);
$container->dependencies()->set('uri', $_SERVER['REQUEST_URI']);
$container->dependencies()->set('request_method', $_SERVER['REQUEST_METHOD']);
$container->dependencies()->set('request', Pd_Make::name('Request'));
$container->dependencies()->set('response', new Response($config));

//Handle request

/** @var $frontController FrontController */
$frontController = Pd_Make::name('FrontController');
$frontController->processRequest();
