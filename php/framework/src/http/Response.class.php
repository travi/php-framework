<?php
/**
 * User: travi
 * Date: Jan 1, 2011
 * Time: 6:27:18 PM
 */

require_once(dirname(__FILE__).'/../dependencyManagement/DependencyManager.class.php');

class Response extends xhtmlPage
{
	private $View;		//Object containing template, css, js, etc information

    public function __construct($config)
    {
        $this->setSiteName($config['siteName']);
        $this->setSiteHeader($config['siteHeader']);
        $this->setTheme('/resources/css/' . $config['theme']['site']);

        if(!empty($config['customFonts']))
        {
            $this->loadCustomFonts($config);
        }

        //temporarily set the layout template here until moving it to $View
        $this->layoutTemplate = $config['template']['layout'];

        //temporarily set smartyConfig to work around the fact that xhtml.class is currently being used
        $this->smartyConfig = $config['smarty'];
        
    }

    private function loadCustomFonts($config)
    {
        foreach($config['customFonts'] as $font)
        {
            $this->addStyleSheet($font);
        }
    }

    public function respond()
    {
        $this->Display();
    }
}
