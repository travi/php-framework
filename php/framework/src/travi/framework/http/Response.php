<?php

namespace travi\framework\http;

use travi\framework\page\AbstractResponse,
    travi\framework\navigation\NavigationObject,
    travi\framework\exception\InvalidHttpStatusException;

class Response extends AbstractResponse
{
    const CREATED  = '201 Created';
    const ACCEPTED = '202 Accepted';

    const BAD_REQUEST  = '400 Bad Request';
    const UNAUTHORIZED = '401 Unauthorized';
    const NOT_FOUND    = '404 Not Found';
    const NOT_ALLOWED  = '405 Method Not Allowed';

    const NOT_IMPLEMENTED = '501 Not Implemented';
    const SERVER_ERROR    = '500 Internal Server Error';

    const SITE_FEED_KEY = 'Site Feed';

    /** @var Request */
    private $request;

    private $definedStatuses = array(
        self::CREATED,
        self::ACCEPTED,
        self::BAD_REQUEST,
        self::UNAUTHORIZED,
        self::NOT_ALLOWED,
        self::NOT_FOUND,
        self::NOT_IMPLEMENTED,
        self::SERVER_ERROR
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

        if (isset($config['siteName'])) {
            $this->setSiteName($config['siteName']);
        }
        if (isset($config['siteHeader'])) {
            $this->setSiteHeader($config['siteHeader']);
        }
        if (isset($config['tagLine'])) {
            $this->setTagLine($config['tagLine']);
        }
        if (!empty($config['siteFeed'])) {
            $this->defineSiteFeed($config['siteFeed']);
        }
        $this->nav = new NavigationObject();  //TODO: need to refactor this
        if (isset($config['nav'])) {
            $this->setPrimaryNav($config['nav']);
        }
        if ($this->request->isAdmin()) {
            $this->setAdminNav($config['adminNav']);
        }

        if (isset($config['template'])) {
            //temporarily set the layout template here until moving it to $View
            $this->setLayoutTemplate($config['template']['layout']);
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

    public function defineSiteFeed($feed)
    {
        $this->addFeed($feed, self::SITE_FEED_KEY);
    }

    public function setStatus($status)
    {
        if (in_array($status, $this->definedStatuses, true)) {
            $this->setHeader('HTTP/1.1 ' . $status);
            $this->setPageTemplate('../status/status.tpl');
            $this->addToResponse('statusCode', $status);
        } else {
            throw new InvalidHttpStatusException($status . ' is an invalid status code');
        }
    }

    public function setHeader($header)
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
