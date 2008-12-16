<?php
/*
 * Created on Jan 26, 2006
 * By Matt Travi
 * programmer@travi.org
 */

abstract class xhtmlPage
{
	protected $title;
	protected $smartyTemplate;
	protected $metatags = array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />');
	protected $stylesheets = array();
	protected $altStyles = array();
	protected $scripts = array();
	protected $jsInits = array();
	protected $feeds = array();
	protected $body;
 	protected $nav;
 	protected $content;
 	protected $debug = false;
 	protected $smartyConfig;

 	public function setTitle($title)
 	{
 		$this->title = $title;
 	}
	
	public function getTitle()
	{
		return $this->title;
	}

 	public function getSmartyConfig()
 	{
		$this->smartyConfig = $this->keyValueFromFile(SMARTY_CONFIG);
 	}

 	public function addToContent($content)
	{
		if(is_array($content))
		{
			foreach($content as $part)
				$this->addToContent($part);
		}
		else if(is_object($content) && is_a($content,'ContentObject'))
		{
			$this->content .= $content;
			$this->checkDependencies($content);
		}
		else $this->content .= $content;
	}

	public function addContentSection($content="")
	{
		$this->addToContent('</div><div class="content">');
		if(!empty($content))
			$this->addToContent($content);
	}
	
	public function getContent()
	{
		return $this->content;
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

 	public function importNavFile()
 	{		
		return $this->keyValueFromFile(NAV_FILE);
 	}

 	public function keyValueFromFile($file)
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

	public function setSubNav($section)
	{
		if(is_array($section))
		{
			foreach($section as $part)
				$this->setSubNav($part);
		}
		else if(is_object($section) && is_a($section,'ContentObject'))
		{
			$this->checkDependencies($section);
			$this->nav['subNav'] .= $section;
		}
		else $this->nav['subNav'] .= $section;
	}

	public function addNavSection($title,$section)
	{
		$this->nav->addSection($title,$section);
	}
	
	public function getNavSection($title)
	{
		return $this->nav->getSection($title);
	}
	
	public function getNavSectionContent($title)
	{
		return $this->nav->getSection($title)->getContent();
	}
	
	public function getNavContentArray()
	{
		return $this->nav->getContentArray();
	}

	public function addNavItem($index, $item)
	{
		$this->nav[$index] .= $item;
	}
	
	public function getNav()
	{
		return $this->nav;
	}

	public function addStyleSheet($sheet,$index="")
	{
		if(!empty($index))
		{
			$this->stylesheets[$index] = $sheet;
		}
		else
			array_push($this->stylesheets,$sheet);
	}
	
	public function getStyleSheets()
	{
		return $this->stylesheets;
	}
	
	public function getAltStyles()
	{
		return $this->altStyles;
	}
	
	public function setTheme($sheet)
	{
		$this->addStyleSheet($sheet,'siteTheme');
	}
	
	public function setPageStyle($sheet)
	{
		$this->addStyleSheet($sheet,'thisPage');		
	}

	public function addAltStyle($sheet)
	{
		array_push($this->altStyles,$sheet);
	}

	public function addJavaScript($script)
	{
		if(!in_array($script,$this->scripts))
		{
			array_push($this->scripts,$script);
		}
	}
	
	public function getScripts()
	{
		return $this->scripts;
	}
	
	public function addJsInit($init)
	{
		array_push($this->jsInits,$init);
	}
	
	public function getJsInits()
	{
		return $this->jsInits;
	}

	public function addFeed($feed)
	{
		array_push($this->feeds,$feed);
	}
	
	public function getFeeds()
	{
		return $this->feeds;
	}

	public function redirect($status,$msg,$location)
	{
		$this->setTitle("Results");

		$this->content = '
			<div class="entry">
				<div class="entry-message">';
		if ($status == "good")
		{
			$this->content .= '
					<div class="good">'.$msg.'</div>';
		}
		else if ($status == "bad" || $status == "undo")
		{
			$this->content .= '
					<div class="bad">'.$msg.'</div>';
		}
		$this->content .= '
					<p>You will be redirected in 5 seconds.</p>
					<p>Feel free to choose another option on the left if you do not want to wait.</p>
				</div>
			</div>';
		array_push($this->metatags,'<meta http-equiv="refresh" content="5; url='.$location.'" />');
	}

	public function Display()
	{
		if($_SERVER['X-Requested-With'] == 'XMLHttpRequest')
		{
			echo $this->content;
		}
		else
		{
			if(!isset($this->smartyConfig))
				$this->getSmartyConfig();
	
	        require_once($this->smartyConfig['pathToSmarty']);
				
			uksort($this->stylesheets, 'strnatcasecmp');
	
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
}
?>
