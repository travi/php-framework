<?php
/*
 * Created on May 6, 2007
 * By Matt Travi
 */

 define('FRAMEWORK_PATH',dirname(__FILE__).'/');
 define('PROCESS','/home/.tookie/travi/include/php/framework/controllers/displayProcess.php');
 
 
 require_once('objects/dependantObject.class.php');

 //Page Objects
 require_once('objects/page/xhtml.class.php');
 importObjects('objects/page/');
 importSiteObjects('page/');

 //Content Objects
 require_once('objects/content/contentObject.class.php');
 //importObjects('objects/content/'); //need to eliminate the duplicate unordered list from elements.class.php
 require_once('objects/content/form.class.php');
 require_once('objects/content/entityList.class.php');
 require_once('objects/content/feedcreator.class.php');
 require_once('objects/content/gallery.class.php');
 require_once('objects/content/list.class.php');
 require_once('objects/content/previewWindow.class.php');

 //Ajax Content Objects
 require_once('objects/content/ajax/fileUpload.ajax.class.php');
 
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
