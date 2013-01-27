<?php

namespace Travi\framework\page;

use Travi\framework\content\navigation\NavigationObject,
    Travi\framework\view\render\JsonRenderer,
    Travi\framework\view\render\HtmlRenderer,
    Travi\framework\utilities\Environment;

abstract class AbstractResponse
{
    const LINK_ATTR_RSS_TYPE = 'application/rss+xml';
    const LINK_ATTR_REL_ALTERNATE = 'alternate';

    protected $siteName;
    protected $title;
    protected $siteHeader;
    protected $subHeader;
    protected $layoutTemplate;
    protected $pageTemplate;
    protected $metatags = array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />');
    protected $clientTemplates = array();
    protected $stylesheets = array();
    protected $altStyles = array();
    protected $scripts = array();
    protected $jsInits = array();
    protected $links = array();
    protected $body;
    /** @var NavigationObject */
    protected $nav;
    /** @var Environment */
    protected $environment;
    protected $content = array();
    protected $currentSiteSection;

    /** @var JsonRenderer */
    protected $jsonRenderer;
    /** @var HtmlRenderer */
    protected $htmlRenderer;

    //////////////////////////////////////////////////////////////////////////
    //                          Configuration                               //
    //////////////////////////////////////////////////////////////////////////

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
        if ($this->environment->isLocal()) {
            $decoratedTitle = '[dev] ';
        } else if ($this->environment->isProduction()) {
            $decoratedTitle = '';
        } else {
            $decoratedTitle = '[test] ';
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
        $this->nav->setSection('main', $section);
    }

    public function getMainNav()
    {
        return $this->nav->getSection('main');
    }


    public function setSubNav($section)
    {
        $this->nav->setSection('subNav', $section);
    }

    public function getSubNav()
    {
        return $this->nav->getSection('subNav');
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

    public function isProduction()
    {
        return $this->environment->isProduction();
    }


    //////////////////////////////////////////////////////////////////////////
    //                          Render                                      //
    //////////////////////////////////////////////////////////////////////////

    public function format()
    {
        $acceptHeader = $_SERVER['HTTP_ACCEPT'];

        if (strstr($acceptHeader, "application/json")) {
            echo $this->jsonRenderer->format($this->getContent());
        } else if (strstr($acceptHeader, "text/xml")) {
            return;
        } else {
            $this->htmlRenderer->setLayoutTemplate($this->getLayoutTemplate());

            $this->htmlRenderer->format($this->getContent(), $this);
        }
    }

    //////////////////////////////////////////////////////////////////////////
    //                          Need Refactoring                            //
    //////////////////////////////////////////////////////////////////////////

    public function getGoogleAnalyticsKey()
    {
        global $config;

        return $config['googleAnalytics']['key'];
    }

    public function redirect($status, $msg, $location)
    {
        $this->setTitle("Results");
        $this->setPageTemplate('../status/result.tpl');

        if ($status === "good") {
            $status = 'good';
        } elseif ($status === "bad" || $status === "undo") {
            $status = 'bad';
        }

        $this->addToResponse('message', $msg);
        $this->addToResponse('status', $status);

        array_push($this->metatags, '<meta http-equiv="refresh" content="5; url='.$location.'" />');
    }

    ////////////////////////////////////////////////////////
    //               Dependencies                         //
    ////////////////////////////////////////////////////////

    /**
     * @param $renderer JsonRenderer
     * @PdInject new:\Travi\framework\view\render\JsonRenderer
     */
    public function setJsonRenderer($renderer)
    {
        $this->jsonRenderer = $renderer;
    }


    /**
     * @param $renderer HtmlRenderer
     * @PdInject new:\Travi\framework\view\render\HtmlRenderer
     */
    public function setHtmlRenderer($renderer)
    {
        $this->htmlRenderer = $renderer;
    }

    /**
     * @param $env Environment
     * @PdInject environment
     */
    public function setEnvironment($env)
    {
        $this->environment = $env;
    }
}

