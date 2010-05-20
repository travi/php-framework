<?php
/*
 * Created on May 6, 2007
 * By Matt Travi
 */
 
 define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
 define('SITE_ROOT', DOC_ROOT.'../');
 define('WEB_ROOT',SITE_ROOT.'../');
 define('FRAMEWORK_PATH',dirname(__FILE__).'/');
 define('INCLUDE_PATH',WEB_ROOT.'include/');
 
 require_once(SITE_ROOT.'config/framework/framework.conf');
 
 define('PROCESS',FRAMEWORK_PATH.'controllers/displayProcess.php');
  
 require_once('objects/dependantObject.class.php');

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
 $uiDeps = parse_ini_file('uiDependencies.ini', true);
 
 
 //Store SCM Revision number
 $version = exec('svnversion');
 $urlPrint = md5($version); 



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
	foreach($objects as $object)
	{
		require_once($object);
	}
	
	$dirs = glob($dir."*");
	foreach($dirs as $innerDir)
	{
		if(is_dir($innerDir))
		{
			importObjectsFromDir($innerDir."/");			
		}
	}
}
?>
