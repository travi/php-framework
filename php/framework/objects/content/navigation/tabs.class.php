<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
require_once('navigation.class.php');
  
class Tabs extends NavigationObject
{	
	function Tabs($options=array())
	{
		$this->addJavaScript('jqueryUi');
		$this->addJsInit("$('.ui-tabs').tabs({selected: 0, fx: {opacity: 'toggle', height: 'toggle'}});");
        $this->setTemplate('components/tabs.tpl');
	}
}
 
?>