<?php
/*
 * Created on Oct 2, 2008
 * By Matt Travi
 * programmer@travi.org
 */

require_once('navigation.class.php');
 
class Accordion extends NavigationObject
{	
	public function __construct($options=array())
	{
		$this->addJavaScript('jqueryUi');
		$this->addJsInit("$('.accordion').accordion({animated: 'easeslide', header: 'dt'});");
        $this->setTemplate('components/accordion.tpl');
	}
}
 
?>