<?php
/**
 * User: travi
 * Date: Jan 1, 2011
 * Time: 6:27:18 PM
 */

//require_once('../../include/')

class Response extends xhtmlPage
{
	private $View;		//Object containing template, css, js, etc information

    public function __construct($config)
    {
        $this->setSiteName($config['siteName']);
        $this->setSiteHeader($config['siteHeader']);

        //temporarily set the layout template here until moving it to $View
        $this->layoutTemplate = $config['template']['layout'];

        //temporarily set smartyConfig to work around the fact that xhtml.class is currently being used
        $this->smartyConfig = $config['smarty'];
        
    }

    public function respond()
    {
        $this->Display();
    }
}
