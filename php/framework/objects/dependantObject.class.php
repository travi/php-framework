<?php

class DependantObject
{
	var $styles = array();
	var $scripts = array();
	var $jsInits = array();

    function getStyles()
    {
    	return $this->styles;
    }
    function getJavaScripts()
    {
    	return $this->scripts;
    }
	function getJsInits()
	{
		return $this->jsInits;
	}
	
	function addStyleSheet($sheet)
	{
		array_push($this->styles,$sheet);
	}
	function addJavaScript($script)
	{
		array_push($this->scripts,$script);
	}
	function addJsInit($init)
	{
		array_push($this->jsInits,$init);
	}
	
	function checkDependencies($object)
	{
		$jScripts = $object->getJavaScripts();
		foreach($jScripts as $script)
		{
			$this->addJavaScript($script);
		}

		$inits = $object->getJsInits();
		foreach($inits as $init)
		{
			$this->addJsInit($init);
		}

		$styles = $object->getStyles();
		foreach($styles as $style)
		{
			$this->addStyleSheet($style);
		}
	}
}
?>