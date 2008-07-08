<?php
/*
 * Created on Jan 26, 2006
 * By Matt Travi
 * programmer@travi.org
 */

class xhtmlPage
{
	var $title;
	var $smartyTemplate;
	var $metatags = array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />');
	var $stylesheets = array();
	var $altStyles = array();
	var $scripts = array();
	var $feeds = array();
	var $body;
	var $onload;
 	var $nav = array();
 	var $content;
 	var $debug = false;
 	var $smartyConfig;

 	//this is meant to be an abstract class
 	//therefore the constructor is
 	//   purposefully left out of this file

 	function setTitle($title)
 	{
 		$this->title = $title;
 	}

 	function getSmartyConfig()
 	{
		$this->smartyConfig = $this->keyValueFromFile(SMARTY_CONFIG);
 	}

 	function importNavFile()
 	{
		
		return $this->keyValueFromFile(NAV_FILE);
 	}

 	function keyValueFromFile($file)
 	{
 		$kvLines = file($file);

 		foreach($kvLines as $kv)
	 	{
		 	$keyVals = explode('=',$kv);
		 	if(count($keyVals) == 2)
		 	{
		 		$keyVals = array_map('trim',$keyVals);
		 		list($key,$value) = $keyVals;
		 		$assocArray["$key"] = $value;
		 	}
		}
		return $assocArray;
 	}

 	function addToContent($content)
	{
		if(is_array($content))
		{
			foreach($content as $part)
				$this->addToContent($part);
		}
		else if(is_object($content) && is_a($content,'ContentObject'))
		{
			$this->addToContent($content->toString());
			$this->checkDependencies($content);
		}
		else $this->content .= $content;
	}

	function addContentSection($content="")
	{
		$this->addToContent('</div><div class="content">');
		if(!empty($content))
			$this->addToContent($content);
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

	function setSubNav($section)
	{
		if(is_array($section))
		{
			foreach($section as $part)
				$this->setSubNav($part);
		}
		else if(is_object($section) && is_a($section,'ContentObject'))
		{
			$this->checkDependencies($section);
			$this->nav['subNav'] .= $section->toString();
		}
		else $this->nav['subNav'] .= $section;
	}

	function addNavSection($section)
	{
		array_push($this->nav,$section);
	}

	function addNavItem($index, $item)
	{
		$this->nav[$index] .= $item;
	}

	function addStyleSheet($sheet,$index="")
	{
		if(!empty($index))
		{
			$this->stylesheets[$index] = $sheet;
		}
		else
			array_push($this->stylesheets,$sheet);
	}
	
	function setTheme($sheet)
	{
		$this->addStyleSheet($sheet,'siteTheme');
	}
	
	function setPageStyle($sheet)
	{
		$this->addStyleSheet($sheet,'thisPage');		
	}

	function addAltStyle($sheet)
	{
		array_push($this->altStyles,$sheet);
	}

	function addJavaScript($script)
	{
		array_push($this->scripts,$script);
	}

	function addFeed($feed)
	{
		array_push($this->feeds,$feed);
	}

	function redirect($status,$msg,$location)
	{
		$this->setTitle("Results");

		$this->content = '
			<div class="entry">
				<div class="entry-message">';
		if ($status == "good")
		{
			$this->content .= '
					<p class="good">'.$msg.'</p>';
		}
		else if ($status == "bad" || $status == "undo")
		{
			$this->content .= '
					<p class="bad">'.$msg.'</p>';
		}
		$this->content .= '
					<p>You will be redirected in 5 seconds.</p>
					<p>Feel free to choose another option on the left if you do not want to wait.</p>
				</div>
			</div>';
		array_push($this->metatags,'<meta http-equiv="refresh" content="5; url='.$location.'" />');
	}

	function Display()
	{
		if(!isset($this->smartyConfig))
			$this->getSmartyConfig();

        require_once($this->smartyConfig['pathToSmarty']);
		
		ksort($this->stylesheets);

		$smarty = new Smarty();

		$smarty->template_dir = $this->smartyConfig['smartyTemplateDir'];
		$smarty->compile_dir = $this->smartyConfig['smartyCompileDir'];
		$smarty->cache_dir = $this->smartyConfig['smartyCacheDir'];
		$smarty->config_dir = $this->smartyConfig['smartyConfigDir'];

		if($this->debug)
		{
			$smarty->force_compile = true;
		}
		else
		{
			$smarty->compile_check = false;
		}

		$smarty->assign('page',$this);
		$smarty->display($this->smartyTemplate);
	}
}
?>
