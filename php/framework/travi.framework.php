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
 
 //Client Objects )(intended to eventually help to resolve dependencies of client dependencies automatically)
 //require_once('objects/client/clientObject.class.php');
 //require_once('objects/client/jquery.class.php');

 //Utility Objects
 importFrameworkObjects('objects/utility/');
 
 
 
 
 //Define JavaScript includes
 
 //jQuery
 define('JQUERY','/resources/shared/js/jquery/jquery.js');
 define('JQUERY_UI','/resources/shared/js/jquery/ui/jquery-ui.js');
 define('JQUERY_LIGHTBOX','/resources/shared/js/jquery/plugins/lightbox/jquery.lightbox.js');
 define('JQUERY_FORM_ALIGN','/resources/shared/js/jquery/plugins/formAlign/jquery.formAlign.js');
 define('JQUERY_VALIDATION','/resources/shared/js/jquery/plugins/validation/jquery.validate.min.js');
 define('JCAROUSEL','/resources/shared/js/jquery/plugins/jcarousel/jquery.jcarousel.js');
 define('JQUERY_WYMEDITOR','/resources/shared/js/jquery/plugins/wymeditor/jquery.wymeditor.pack.js');
 define('JQUERY_URL_PARSER','/resources/shared/js/jquery/plugins/url/jquery.url.js');
 define('JQUERY_PXEM','/resources/shared/js/jquery/plugins/pxem/jquery.pxem.js');
 define('JQUERY_EQUAL_HEIGHTS','/resources/shared/js/jquery/plugins/equalHeights/jquery.equalHeights.js');
 
 //reflection.js
 define('REFLECTION_JS','/resources/shared/js/reflection/reflection.js');
 
 //png fix
 define('PNG_FIX','/resources/shared/js/pngFix/DD_belatedPNG.js');



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
