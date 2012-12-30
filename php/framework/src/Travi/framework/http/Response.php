<?php

namespace Travi\framework\http;

use Travi\framework\page\AbstractResponse,
    Travi\framework\content\navigation\NavigationObject,
    Travi\framework\exception\InvalidHttpStatusException;

class Response extends AbstractResponse
{
    const NOT_ALLOWED = '405 Method Not Allowed';
    const NOT_IMPLEMENTED = '501 Not Implemented';

    const SITE_FEED_KEY = 'Site Feed';

    /** @var Request */
    private $request;

    private $definedStatuses = array(
        self::NOT_ALLOWED,
        self::NOT_IMPLEMENTED
    );

    /** @var string */
    private $tagLine;
    private $config;


    /**
     * @param $request Request
     * @PdInject request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @PdInject config
     * @param $config
     */
    public function init($config)
    {
        $this->config = $config;

        $this->setSiteName($config['siteName']);
        $this->setSiteHeader($config['siteHeader']);
        $this->setTagLine($config['tagLine']);
        if (!empty($config['siteFeed'])) {
            $this->defineSiteFeed($config['siteFeed']);
        }
        $this->nav = new NavigationObject();  //TODO: need to refactor this
        $this->setPrimaryNav($config['nav']);
        if ($this->request->isAdmin()) {
            $this->setAdminNav($config['adminNav']);
        }

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

    public function setStatus($status)
    {
        if (in_array($status, $this->definedStatuses)) {
            $this->setHeader('HTTP/1.1 ' . $status);
            $this->setPageTemplate('../status/status.tpl');
            $this->addToResponse('status', $status);
        } else {
            throw new InvalidHttpStatusException();
        }
    }

    protected function setHeader($header)
    {
        header($header);
    }

    public function setAdminNav($nav)
    {
        if ($this->request->isAdmin()) {
            $this->nav->addSection('admin', $nav);
        }
    }

    public function getAdminNav()
    {
        return $this->nav->getSection('admin');
    }
}
