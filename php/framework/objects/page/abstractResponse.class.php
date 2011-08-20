<?php
/*
 * Created on Jan 26, 2006
 * By Matt Travi
 * programmer@travi.org
 */

abstract class AbstractResponse
{
    protected $siteName;
    protected $title;
    protected $siteHeader;
    protected $subHeader;
    protected $layoutTemplate;
    protected $pageTemplate;
    protected $metatags = array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />');
    protected $clientTemplates = array();
    private   $externalClientTemplates = array();
    const LINK_ATTR_RSS_TYPE = 'application/rss+xml';
    const LINK_ATTR_REL_ALTERNATE = 'alternate';
    /** @var DependencyManager */
    protected $dependencyManager;
    protected $stylesheets = array();
    protected $altStyles = array();
    protected $scripts = array();
    protected $jsInits = array();
    protected $links = array();
    protected $body;
    protected $nav;
    protected $content = array();
    protected $currentSiteSection;
    protected $smartyConfig;
    protected $smarty;


    //////////////////////////////////////////////////////////////////////////
    //                          Configuration                               //
    //////////////////////////////////////////////////////////////////////////

    public function importNavFile()
    {
        return $this->yaml2Array(NAV_FILE);
    }

    public function keyValueFromFile($file)
    {
        $kvLines = file($file);

        foreach ($kvLines as $kv) {
            $keyVals = explode('=', $kv);
            if (count($keyVals) == 2) {
                $keyVals = array_map('trim', $keyVals);
                list($key,$value) = $keyVals;
                $assocArray["$key"] = $value;
            }
        }
        return $assocArray;
    }

    public function yaml2Array($file)
    {
        return Spyc::YAMLLoad($file);
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
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getDecoratedTitle()
    {
        if (ENV == 'development') {
            $decoratedTitle = '[dev] ';
        } else if (ENV == 'test') {
            $decoratedTitle = '[test] ';
        } else {
            $decoratedTitle = '';
        }

        $decoratedTitle .= $this->title;

        if (isset($this->siteName)) {
            $decoratedTitle .= ' | ' . $this->getSiteName();
        }

        return $decoratedTitle;
    }

    public function setSiteHeader($header = '')
    {
        if (!empty($header)) {
            $this->siteHeader = $header;
        } else {
            $this->siteHeader = $this->siteName;
        }
    }

    public function getHeader()
    {
        return $this->siteHeader;
    }

    public function setSubHeader($subHeader)
    {
        $this->subHeader = $subHeader;
    }

    public function getSubHeader()
    {
        return $this->subHeader;
    }

    public function getSmartyConfig()
    {
        $this->smartyConfig = $this->yaml2Array(SMARTY_CONFIG);
    }


    //////////////////////////////////////////////////////////////////////////
    //                              Content                                 //
    //////////////////////////////////////////////////////////////////////////

    public function addToResponse($desc, $content)
    {
            $this->content[$desc] = $content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    //TODO: maybe use this (once modified to work with new flow) instead of arrays for adding a section
    //    public function addContentSection($content="")
    //    {
    //        $this->addToContent('</div><div class="content">');
    //        if(!empty($content))
    //            $this->addToContent($content);
    //    }

    public function getContent()
    {
        return $this->content;
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Dependencies                                //
    //////////////////////////////////////////////////////////////////////////

    public function addDependency($dependency, $category, $index="")
    {
        if (!isset($this->dependencyManager)) {
            $this->dependencyManager = new DependencyManager();
        }

        $this->dependencyManager->addDependency($dependency, $category, $index);
    }

    public function getDependencyList($category)
    {
        if (isset($this->dependencyManager)) {
            return $this->dependencyManager->getDependencies($category);
        } else {
            return array();
        }
    }

    public function addDependencies($dependencies)
    {
        if (!isset($this->dependencyManager)) {
            $this->dependencyManager = new DependencyManager();
        }

        $this->dependencyManager->addDependencies($dependencies);
        if (!empty($dependencies['links'])) {
            foreach ($dependencies['links'] as $link) {
                $this->addLinkTag($link['link'], $link['rel'], $link['title'], $link['type']);
            }
        }
        if (!empty($dependencies['feeds'])) {
            foreach ($dependencies['feeds'] as $feed) {
                $this->addFeed($feed['link'], $feed['title']);
            }
        }
    }

    public function addStyleSheet($sheet, $index="")
    {
        $this->addDependency($sheet, 'css', $index);
    }

    public function getStyleSheets()
    {
        return $this->getDependencyList('css');
    }

    public function addAltStyle($sheet)
    {
        array_push($this->altStyles, $sheet);
    }

    public function getAltStyles()
    {
        return $this->altStyles;
    }

    public function setTheme($sheet)
    {
        $this->addStyleSheet($sheet, 'siteTheme');
    }

    public function setPageStyle($sheet)
    {
        $this->addStyleSheet($sheet, 'thisPage');
    }

    public function addJavaScript($script)
    {
        $this->addDependency($script, 'js');
    }

    public function getScripts()
    {
        return $this->getDependencyList('js');
    }

    public function addJsInit($init)
    {
        $this->addDependency($init, 'jsInit');
    }

    public function getJsInits()
    {
        return $this->getDependencyList('jsInit');
    }

    public function getValidations()
    {
        return $this->getDependencyList('validations');
    }

    public function getProperFile($file)
    {
        global $config;

        if (ENV !== 'development' && $config['debug'] !== true) {
            return preg_replace('/\/(css|js)\//', '/min/$1/', $file, 1);
        } else {
            return $file;
        }
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Other Tags                                  //
    //////////////////////////////////////////////////////////////////////////

    public function addLinkTag($link,$rel,$title='',$type='')
    {
        array_push(
            $this->links,
            array(
                 'link'  => $link,
                 'title' => $title,
                 'type'  => $type,
                 'rel'   => $rel
            )
        );
    }

    public function getLinkTags()
    {
        return $this->links;
    }

    public function addFeed($feed, $title='RSS')
    {
        $this->addLinkTag($feed, self::LINK_ATTR_REL_ALTERNATE, $title, self::LINK_ATTR_RSS_TYPE);
    }

    public function addMetaTag($tag)
    {
        array_push($this->metatags, $tag);
    }

    public function getMetaTags()
    {
        return $this->metatags;
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Navigation                                  //
    //////////////////////////////////////////////////////////////////////////

    public function setPrimaryNav($section)
    {
        $navArray = $section;

        //        foreach($navArray as &$navItem){
        //            if(is_array($navItem)){
        //                $navItem = $navItem['link'];
        //            }
        //        }

        $this->nav->setSection('main', $navArray);
    }

    public function getMainNav()
    {
        return $this->nav->getSection('main');
    }

    public function setAdminNav($section)
    {
        if (is_string($section)) {
            $section = $this->yaml2Array($section);
        }
        $this->nav->setSection('admin', $section);
    }

    public function getAdminNav()
    {
        return $this->nav->getSection('admin');
    }


    public function setSubNav($section)
    {
        $this->nav->setSection('subNav', $section);
    }

    public function getSubNav()
    {
        return $this->nav->getSection('subNav');
    }

    public function addNavSection($title, $section)
    {
        if (is_string($section)) {
            $section = $this->yaml2Array($section);
        }
        $this->nav->addSection($title, $section);
    }

    public function getNavSection($title)
    {
        return $this->nav->getSection($title);
    }

    public function addNavItem($index, $item)
    {
        $this->nav[$index] .= $item;
    }

    public function getNav()
    {
        return $this->nav;
    }


    //////////////////////////////////////////////////////////////////////////
    //                              URL                                     //
    //////////////////////////////////////////////////////////////////////////

    public function getSiteSection()
    {
        if (empty($this->currentSiteSection)) {
            $navString = $_SERVER['REQUEST_URI'];
            $parts = explode('/', $navString);
            if ($parts[1] === 'admin') {
                $this->currentSiteSection = $parts[2];
            } else {
                $this->currentSiteSection = $parts[1];
            }
        }
        return $this->currentSiteSection;
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Templates                                   //
    //////////////////////////////////////////////////////////////////////////

    public function setLayoutTemplate($template)
    {
        $this->layoutTemplate = $template;
    }

    public function getLayoutTemplate()
    {
        return $this->layoutTemplate;
    }

    public function setPageTemplate($template)
    {
        $this->pageTemplate = $template;
    }

    public function getPageTemplate()
    {
        return $this->pageTemplate;
    }

    public function addClientTemplate($name, $template)
    {
        $this->clientTemplates[$name] = $template;
    }

    public function getClientTemplates()
    {
        return $this->clientTemplates;
    }

    public function getExternalClientTemplates()
    {
        return $this->getDependencyList('clientTemplates');
    }

    public function smartyInit()
    {
        global $config;

        if (!isset($this->smartyConfig)) {
            $this->getSmartyConfig();
        }

        include_once $this->smartyConfig['pathToSmarty'];

        $smarty = new Smarty();

        $smarty->template_dir = array(
            $this->smartyConfig['siteTemplateDir'],
            $this->smartyConfig['sharedTemplateDir']
        );
        $smarty->compile_dir = $this->smartyConfig['smartyCompileDir'];
        $smarty->cache_dir = $this->smartyConfig['smartyCacheDir'];
        $smarty->config_dir = $this->smartyConfig['smartyConfigDir'];

        if ($config['debug']) {
            $smarty->force_compile = true;
        } else {
            $smarty->compile_check = false;
        }

        $this->smarty = $smarty;
    }

    public function getSmarty()
    {
        if (empty($this->smarty)) {
            $this->smartyInit();
        }
        return $this->smarty;
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Render                                      //
    //////////////////////////////////////////////////////////////////////////

    protected function format()
    {
        $acceptHeader = $_SERVER['HTTP_ACCEPT'];

        if (strstr($acceptHeader, "application/json")) {
            header('Content-Type: application/json');
            //TODO: if content is array of size 1, should only encode that element rather than the array
            echo json_encode($this->getContent());
        } else if (strstr($acceptHeader, "text/xml")) {
            return;
        } else {
            if (isset($this->dependencyManager)) {
                $this->dependencyManager->resolveContentDependencies($this->getContent());
                $this->dependencyManager->addCacheBusters();
            }

            $smarty = $this->getSmarty();

            $smarty->clearAllAssign();
            $smarty->assign('page', $this);
            $smarty->display($this->getLayoutTemplate());
        }
    }

    public function Display()
    {
        $this->format();
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Need Refactoring                            //
    //////////////////////////////////////////////////////////////////////////

    public function goog_analytics()
    {
        if (ENV === 'production') {
            return "		<script type=\"text/javascript\">

            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '".GOOGLE_ANALYTICS_KEY."']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' :
                    'http://www') + '.google-analytics.com/ga.js';
                (document.getElementsByTagName('head')[0]
                    || document.getElementsByTagName('body')[0]).appendChild(ga);
            })();

        </script>";
        }
    }

    public function redirect($status, $msg, $location)
    {
        $this->setTitle("Results");

        $this->content = '
            <div class="entry">
                <div class="entry-message">';
        if ($status == "good") {
            $this->content .= '
                    <div class="good">'.$msg.'</div>';
        } elseif ($status == "bad" || $status == "undo") {
            $this->content .= '
                    <div class="bad">'.$msg.'</div>';
        }
        $this->content .= '
                    <p>You will be redirected in 5 seconds.</p>
                    <p>Feel free to choose another option on the left if you do not want to wait.</p>
                </div>
            </div>';
        array_push($this->metatags, '<meta http-equiv="refresh" content="5; url='.$location.'" />');
    }
}
