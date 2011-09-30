<?php

require_once dirname(__FILE__) . '/../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../http/Request.class.php';
require_once dirname(__FILE__).'/../http/Response.class.php';
require_once dirname(__FILE__).'/../controller/front/front.controller.php';

// Report all errors except E_NOTICE (should try to use this for development)
//error_reporting(E_ALL ^ E_NOTICE);
// Report all errors except E_NOTICE and E_WARNING (better for production)
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);

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

//global vars for legacy stuff
//TODO: clean this up once refactored
$uiDeps = Spyc::YAMLLoad(dirname(__FILE__).'/../../../../config/uiDependencies.yaml');
$siteUiDeps = Spyc::YAMLLoad(SITE_ROOT.'config/dependencies/components.yaml');

$config['uiDeps']['widgets'] = $uiDeps;
$config['uiDeps']['siteWidgets'] = $siteUiDeps;
$config['uiDeps']['pages'] = Spyc::YAMLLoad(SITE_ROOT.'config/pageDependencies.yml');

//to make legacy stuff work
//TODO: remove this once refactored
$uiDeps = array_merge_recursive($uiDeps, $siteUiDeps);

//Add Dependencies
$container->dependencies()->set('config', $config);

$container->dependencies()->set('uri', $_SERVER['REQUEST_URI']);
$container->dependencies()->set('request_method', $_SERVER['REQUEST_METHOD']);
$container->dependencies()->set('enhancementVersion', $_COOKIE[Request::ENHANCEMENT_VERSION_KEY]);
$container->dependencies()->set('request', Pd_Make::name('Request'));

$container->dependencies()->set('response', new Response($config));
$container->dependencies()->set('Smarty', smartyInit());
$container->dependencies()->set('fileSystem', fileSystemInit($config['sitePath'], '/home/travi/include'));
$container->dependencies()->set('environment', environmentInit($config['productionUrl']));
$container->dependencies()->set(
    'dependencyManager',
    dmInit($config['uiDeps']['pages'], $config['theme']['site'])
);

//Handle request

/** @var $frontController FrontController */
$frontController = Pd_Make::name('FrontController');
$frontController->processRequest();

/**
 * @return Smarty
 */
function smartyInit()
{
    global $config;

    $smartyConfig = $config['smarty'];

    include_once $smartyConfig['pathToSmarty'];

    $smarty = Pd_Make::name('Smarty');

    $smarty->template_dir = array(
        $smartyConfig['siteTemplateDir'],
        $smartyConfig['sharedTemplateDir']
    );
    $smarty->compile_dir = $smartyConfig['smartyCompileDir'];
    $smarty->cache_dir = $smartyConfig['smartyCacheDir'];
    $smarty->config_dir = $smartyConfig['smartyConfigDir'];

    if ($config['debug']) {
        $smarty->force_compile = true;
    } else {
        $smarty->compile_check = false;
    }

    return $smarty;
}

/**
 * @param $pageDepLists
 * @param $theme
 * @return DependencyManager
 */
function dmInit($pageDepLists, $theme)
{
    include_once dirname(__FILE__) . '/../dependencyManagement/DependencyManager.class.php';

    /** @var $dependencyManager DependencyManager */
    $dependencyManager = Pd_Make::name('DependencyManager');
    $dependencyManager->setPageDependenciesLists($pageDepLists);
    $dependencyManager->setSiteTheme('/resources/css/' . $theme);
    return $dependencyManager;
}

/**
 * @param $sitePath
 * @param $sharedPath
 * @return FileSystem
 */
function fileSystemInit($sitePath, $sharedPath)
{
    include_once dirname(__FILE__) . '/../utilities/FileSystem.php';

    /** @var $fileSystem FileSystem */
    $fileSystem = Pd_Make::name('FileSystem');
    $fileSystem->setSitePath($sitePath);
    $fileSystem->setSharedPath($sharedPath);
    return $fileSystem;
}

/**
 * @param $prodUrl
 * @return Environment
 */
function environmentInit($prodUrl)
{
    include_once dirname(__FILE__) . '/../utilities/Environment.php';

    /** @var $environment Environment */
    $environment = Pd_Make::name('Environment');
    $environment->setProductionUrl($prodUrl);
    return $environment;
}