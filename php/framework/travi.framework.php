<?php

$config = array();
session_start();
if (isset($_SESSION['debug'])) {
    $config['debug'] = true;
} else {
    $config['debug'] = DEBUG;
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
define('INCLUDE_PATH', WEB_ROOT . 'include/');

require_once FRAMEWORK_PATH.'../thirdparty/spyc/spyc.php';
require_once SITE_ROOT.'config/framework/framework.conf';

define('PROCESS', FRAMEWORK_PATH . 'controllers/displayProcess.php');

require_once 'objects/dependantObject.class.php';


importFrameworkObjects('src/dependencyManagement/');

//Page Objects
importFrameworkObjects('objects/page/');
importSiteObjects('page/');

//Content Objects
importFrameworkObjects('objects/content/');

//Ajax Content Objects
//require_once('objects/content/ajax/fileUpload.ajax.class.php');

//Utility Objects
importFrameworkObjects('objects/utility/');


//Define UI Dependencies
$uiDeps = Spyc::YAMLLoad(INCLUDE_PATH.'config/uiDependencies.yaml');
$siteUiDeps = Spyc::YAMLLoad(SITE_ROOT.'config/dependencies/components.yaml');
$uiDeps = array_merge_recursive($uiDeps, $siteUiDeps);


function importFrameworkObjects($relPath)
{
    importObjectsFromDir(FRAMEWORK_PATH.$relPath);
}

function importSiteObjects($relPath)
{
    importObjectsFromDir(SITE_OBJECTS.$relPath);
}

function importObjectsFromDir($dir)
{
    $objects = glob($dir."*.class.php");
    foreach ($objects as $object) {
        include_once $object;
    }

    $dirs = glob($dir."*");
    foreach ($dirs as $innerDir) {
        if (is_dir($innerDir)) {
            importObjectsFromDir($innerDir."/");
        }
    }
}
