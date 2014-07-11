<?php

use travi\framework\http\Response,
    travi\framework\http\Request,
    travi\framework\utilities\FileSystem,
    travi\framework\dependencyManagement\DependencyManager,
    travi\framework\utilities\Environment,
    travi\framework\view\render\HtmlRenderer;

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    __DIR__ . '/../../../thirdparty/PHP-Dependency/library/'
);





//Initialize Dependency Injection Container
$container = Pd_Container::get();







//Get Config
//Temp definition
if (!defined('DOC_ROOT')) {
    define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
}
if (!defined('SITE_ROOT')) {
    define('SITE_ROOT', DOC_ROOT . '../');
}
if (!defined('FRAMEWORK_PATH')) {
    define('FRAMEWORK_PATH', SITE_ROOT . 'vendor/travi/framework/php/framework/');
}

$config             = Spyc::YAMLLoad(SITE_ROOT . 'config/siteConfig.yml');
$config['sitePath'] = SITE_ROOT;

$container->dependencies()->set('uri', $_SERVER['REDIRECT_URL']);
$container->dependencies()->set('request_method', $_SERVER['REQUEST_METHOD']);
$container->dependencies()->set('enhancementVersion', $_COOKIE[Request::ENHANCEMENT_VERSION_KEY]);
$container->dependencies()->set('request', Pd_Make::name('travi\\framework\\http\\Request'));

/** @var FileSystem $fileSystem */
$fileSystem = fileSystemInit($config['sitePath'], FRAMEWORK_PATH . '../../');
$container->dependencies()->set('fileSystem', $fileSystem);

//global vars for legacy stuff
//TODO: clean this up once refactored
$uiDeps             = Spyc::YAMLLoad(__DIR__ . '/../../../../config/uiDependencies.yaml');
$siteUiDeps         = array();
$siteComponentsFile = SITE_ROOT . 'config/dependencies/components.yaml';
if ($fileSystem->fileExists($siteComponentsFile)) {
    $siteUiDeps = Spyc::YAMLLoad($siteComponentsFile);
}

//to make legacy stuff work
//TODO: remove this once refactored
$uiDeps = array_merge_recursive($uiDeps, $siteUiDeps);

$config['uiDeps']['widgets']     = $uiDeps;
$config['uiDeps']['siteWidgets'] = $siteUiDeps;
$config['uiDeps']['pages']       = Spyc::YAMLLoad(SITE_ROOT.'config/pageDependencies.yml');


$config['nav'] = Spyc::YAMLLoad(SITE_ROOT.'config/nav.yml');
if (isset($config['nav']['admin'])) {
    $config['adminNav'] = $config['nav']['admin'];
    unset($config['nav']['admin']);
}

$config['docRoot'] = DOC_ROOT;



$container->dependencies()->set('environment', environmentInit($config['productionUrl']));
/** @var Environment $environment */
$environment = $container->dependencies()->get('environment');

$config['debug'] = $environment->isLocal();


//Add Dependencies
$container->dependencies()->set('config', $config);
$container->dependencies()->set('uiDeps', $uiDeps);

if (defined('DB_HOSTNAME')) {
    $container->dependencies()->set('db', dbInit());
}

$container->dependencies()->set('session', Pd_Make::name('travi\\framework\\http\\Session'));
$container->dependencies()->set(
    'dependencyManager',
    dmInit($config['uiDeps']['pages'], $config['theme']['site'])
);
$container->dependencies()->set('Smarty', smartyInit($config['smarty'], $config['debug']));
$container->dependencies()->set(
    'htmlRenderer',
    Pd_Make::name('travi\\framework\\view\\render\\HtmlRenderer')
);

$container->dependencies()->set('response', Pd_Make::name('travi\\framework\\http\\Response'));



/**
 * @return PDO
 */
function dbInit()
{
    $pdo = new PDO(
        'mysql:host=' . DB_HOSTNAME . ';dbname=' . DEF_DB_NAME,
        DB_ADMIN_USERNAME,
        DB_ADMIN_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
}

/**
 * @param $sitePath
 * @param $sharedPath
 * @return FileSystem
 */
function fileSystemInit($sitePath, $sharedPath)
{
    /** @var $fileSystem FileSystem */
    $fileSystem = Pd_Make::name('travi\\framework\\utilities\\FileSystem');
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
    /** @var $environment Environment */
    $environment = Pd_Make::name('travi\\framework\\utilities\\Environment');
    $environment->setProductionUrl($prodUrl);
    return $environment;
}

/**
 * @param $smartyConfig
 * @param $debug
 * @return Smarty
 */
function smartyInit($smartyConfig, $debug)
{
    $smarty = Pd_Make::name('Smarty');

    //stops at first match, so site path is first to allow overriding of framework templates
    $smarty->template_dir = array(
        SITE_ROOT . $smartyConfig['siteTemplateDir'],
        FRAMEWORK_PATH . '../../php/templates'
    );
    $smarty->compile_dir  = $smartyConfig['smartyCompileDir'];
    $smarty->cache_dir    = $smartyConfig['smartyCacheDir'];
    $smarty->config_dir   = $smartyConfig['smartyConfigDir'];

    if ($debug) {
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
    /** @var $dependencyManager DependencyManager */
    $dependencyManager = Pd_Make::name('travi\\framework\\dependencyManagement\\DependencyManager');
    $dependencyManager->setPageDependenciesLists($pageDepLists);
    $dependencyManager->setSiteTheme('/resources/css/' . $theme);
    return $dependencyManager;
}