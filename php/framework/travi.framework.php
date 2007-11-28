<?php
/*
 * Created on May 6, 2007
 * By Matt Travi
 */

 define('FRAMEWORK_PATH',dirname(__FILE__).'/');

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

 //Utility Objects
 importObjects('objects/utility/');



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
