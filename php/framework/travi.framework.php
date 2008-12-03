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
 importObjects('objects/page/');
 importSiteObjects('page/');

 //Content Objects
 importObjects('objects/content/'); 

 //Ajax Content Objects
 //require_once('objects/content/ajax/fileUpload.ajax.class.php');
 
 //Client Objects )(intended to eventually help to resolve dependencies of client dependencies automatically)
 //require_once('objects/client/clientObject.class.php');
 //require_once('objects/client/jquery.class.php');

 //Utility Objects
 importObjects('objects/utility/');
 
 
 
 
 //Define JavaScript includes
 
 //jQuery
 define('JQUERY','/resources/shared/js/jquery/jquery.js');
 define('JQUERY_UI','/resources/shared/js/jquery/ui/jquery-ui.min.js');
 define('JQUERY_LIGHTBOX','/resources/shared/js/jquery/plugins/lightbox/jquery.lightbox.js');
 define('JQUERY_FORM_ALIGN','/resources/shared/js/jquery/plugins/formAlign/jquery.formAlign.js');
 define('JCAROUSEL','/resources/shared/js/jquery/plugins/jcarousel/jquery.jcarousel.js');
 define('JQUERY_WYMEDITOR','/resources/shared/js/jquery/plugins/wymeditor/jquery.wymeditor.pack.js');
 
 //reflection.js
 define('REFLECTION_JS','/resources/shared/js/reflection/reflection.js');



	function importObjects($relPath)
	{
		$objects = glob(FRAMEWORK_PATH.$relPath."*.class.php");
		foreach($objects as $object)
		{
			require_once($object);
		}
	}

	function importSiteObjects($relPath)
	{
		$objects = glob(SITE_OBJECTS.$relPath."*.class.php");
		foreach($objects as $object)
		{
			require_once($object);
		}
	}
?>
