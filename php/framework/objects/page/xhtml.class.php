<?php
/*
 * Created on Jan 26, 2006
 * By Matt Travi
 * programmer@travi.org
 */

abstract class xhtmlPage
{
	protected $siteName;
	protected $title;
	protected $layoutTemplate;
    protected $pageTemplate;
	protected $metatags = array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />');
	protected $stylesheets = array();
	protected $altStyles = array();
	protected $scripts = array();
	protected $jsInits = array();
	protected $feeds = array();
	protected $body;
 	protected $nav;
 	protected $content;
 	protected $smartyConfig;
 	protected $urlFingerprint;

    public function setLayoutTemplate($template)
    {
        $this->layoutTemplate = $template;
    }

    public function setPageTemplate($template)
    {
        $this->pageTemplate = $template;
    }

    public function getPageTemplate()
    {
        return $this->pageTemplate;
    }
	
	public function setSiteName($name)
	{
		$this->siteName = $name;
	}
	
	public function getSiteName()
	{
		return $this->siteName;
	}

 	public function setTitle($title)
 	{
 		if(ENV == 'development')
			$this->title = '[dev] ';
 		else if(ENV == 'test')
			$this->title = '[test] ';
		else
			$this->title = '';
 		$this->title .= $title;
		if(isset($this->siteName))
			$this->title .= ' | '.$this->getSiteName();
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

    public function setContent($content)
    {
        $this->content = $content;
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
	
	public function setUrlFingerprint($fingerprint)
	{
		$this->urlFingerprint = $fingerprint;
	}
	
	public function getUrlFingerprint()
	{
		return $this->urlFingerprint;
	}

	public function addStyleSheet($sheet,$index="")
	{
		if(!in_array($sheet,$this->stylesheets))
		{
			if(!empty($index))
			{
				$this->stylesheets[$index] = $sheet;
			}
			else
				array_push($this->stylesheets,$sheet);
		}
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
    	global $uiDeps;
    	
		if(!empty($uiDeps[$script]))
		{
			if(!empty($uiDeps[$script]['jsDependencies']))
			{
				foreach($uiDeps[$script]['jsDependencies'] as $dependency)
				{
					$this->addJavaScript($dependency);
				}
			}
			if(!empty($uiDeps[$script]['cssDependencies']))
			{
				foreach($uiDeps[$script]['cssDependencies'] as $dependency)
				{
					if($dependency === 'jqueryUiTheme')
					{
						$this->addStyleSheet(JQUERY_UI_THEME);
					} 
					else if($dependency === 'jcarsouselSkin')
					{
						$this->addStyleSheet(JCAROUSEL_SKIN);
					} 
					else
					{
						$this->addStyleSheet($dependency);
					}
				}
			}
			if(!empty($uiDeps[$script]['local']))
			{
				$script = $uiDeps[$script]['local'];
			}
			else echo 'local is empty!'; //TODO: handle properly
		}
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
	
	public function getProperFile($file)
	{
		global $config;
		
		if(ENV !== 'development' && $config['debug'] !== true)
		{
			return preg_replace('/\/(css|js)\//','/min/$1/',$file,1);
		}
		else
		{
			return $file;
		}
	}
	
	public function goog_analytics() 
	{
		if(ENV === 'production')
		{
			return "		<script type=\"text/javascript\">
		
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', '".GOOGLE_ANALYTICS_KEY."']);
			_gaq.push(['_trackPageview']);
		
			(function() {
				var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
				ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(ga);
			})();
		
		</script>";
		}
	}

	public function addFeed($feed)
	{
		array_push($this->feeds,$feed);
	}
	
	public function getFeeds()
	{
		return $this->feeds;
	}
	
	public function addMetaTag($tag)
	{
		array_push($this->metatags,$tag);
	}
	
	public function getMetaTags()
	{
		return $this->metatags;
	}
	
	public function getWpHead()
	{
		if(function_exists('wp_head') && strpos($_SERVER['REQUEST_URI'], 'blog'))
		{
			wp_head();
		}
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

    protected function format()
    {
		global $config;
		$acceptHeader = $_SERVER['HTTP_ACCEPT'];

		if (strstr($acceptHeader,"application/json")){
            header('Content-Type: application/json');
            echo json_encode($this->getContent());
		} else if (strstr($acceptHeader,"text/xml")){
			return;
		} else if (strstr($acceptHeader,"text/html")){
			if(!isset($this->smartyConfig))
				$this->getSmartyConfig();

	        require_once($this->smartyConfig['pathToSmarty']);

			uksort($this->stylesheets, 'strnatcasecmp');

			$smarty = new Smarty();

			$smarty->template_dir = array($this->smartyConfig['siteTemplateDir'],$this->smartyConfig['sharedTemplateDir']);
			$smarty->compile_dir = $this->smartyConfig['smartyCompileDir'];
			$smarty->cache_dir = $this->smartyConfig['smartyCacheDir'];
			$smarty->config_dir = $this->smartyConfig['smartyConfigDir'];

			if($config['debug'])
			{
				$smarty->force_compile = true;
			}
			else
			{
				$smarty->compile_check = false;
			}

			$smarty->assign('page',$this);
			$smarty->display($this->layoutTemplate);
        }
    }

	public function Display()
	{
		$this->format();
	}
}
?>
