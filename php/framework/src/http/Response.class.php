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

    /** @var string */
    private $tagLine;

    public function __construct($config)
    {
        $this->setSiteName($config['siteName']);
        $this->setSiteHeader($config['siteHeader']);
        $this->setTagLine($config['tagLine']);
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

    /**
     * @param  $tagLine
     * @return void
     */
    public function setTagLine($tagLine)
    {
        $this->tagLine = $tagLine;
    }

    /**
     * @return string
     */
    public function getTagLine()
    {
        return $this->tagLine;
    }

    public function respond()
    {
        $this->Display();
    }
}
