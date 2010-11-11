<?php

abstract class DependantObject
{
	protected $styles = array();
	protected $scripts = array();
	protected $jsInits = array();

    public function getStyles()
    {
    	return $this->styles;
    }
    public function getJavaScripts()
    {
    	return $this->scripts;
    }
	public function getJsInits()
	{
		return $this->jsInits;
	}
	
	public function addStyleSheet($sheet)
	{
		array_push($this->styles,$sheet);
	}
	public function addJavaScript($script)
	{
		array_push($this->scripts,$script);
	}
	public function addJsInit($init)
	{
		array_push($this->jsInits,$init);
	}
	
	public function checkDependencies($object)
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

    public function getDependencies()
    {
        return array(   'scripts'   => $this->getJavaScripts(),
                        'jsInits'   => $this->getJsInits(),
                        'styles'    => $this->getStyles());
    }
}
?>