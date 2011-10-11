<?php

require_once dirname(__FILE__).'/../../objects/dependantObject.class.php';
require_once dirname(__FILE__).'/../../objects/content/contentObject.class.php';
require_once dirname(__FILE__).'/../../objects/content/navigation/navigation.class.php';
require_once dirname(__FILE__) . '/../../objects/page/abstractResponse.class.php';

class Response extends AbstractResponse
{
    const SITE_FEED_KEY = 'Site Feed';

    /** @var string */
    private $tagLine;
    private $config;

    public function __construct($config)
    {
        $this->setSiteName($config['siteName']);
        $this->setSiteHeader($config['siteHeader']);
        $this->setTagLine($config['tagLine']);
        if (!empty($config['siteFeed'])) {
            $this->defineSiteFeed($config['siteFeed']);
        }
        $this->nav = new NavigationObject();  //TODO: need to refactor this
        $this->setPrimaryNav($config['nav']);

        //temporarily set the layout template here until moving it to $View
        $this->setLayoutTemplate($config['template']['layout']);
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

    public function defineSiteFeed($feed)
    {
        $this->addFeed($feed, self::SITE_FEED_KEY);
    }
}
