<?php

class ContentObject
{
	var $scripts = array();
	var $styles = array();

    function getStyles()
    {
    	return $this->styles;
    }

    function getJavaScripts()
    {
    	return $this->scripts;
    }
    function toString()
    {
    	return '';
    }
	function addStyleSheet($sheet)
	{
		array_push($this->styles,$sheet);
	}
	function addJavaScript($script)
	{
		array_push($this->scripts,$script);
	}
	function checkDependencies($object)
	{
		$jScripts = $object->getJavaScripts();
		foreach($jScripts as $script)
		{
			$this->addJavaScript($script);
		}

		$styles = $object->getStyles();
		foreach($styles as $style)
		{
			$this->addStyleSheet($style);
		}
	}
}
?>