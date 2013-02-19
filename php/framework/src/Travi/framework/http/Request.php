<?php

namespace Travi\framework\http;

use Travi\framework\page\AbstractResponse;
 
class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const DELETE = 'DELETE';

    const ENHANCEMENT_VERSION_KEY = 'enhancementVersion';
    const BASE_ENHANCEMENT = 'base';
    const SMALL_ENHANCEMENT = 'small';
    const LARGE_ENHANCEMENT = 'large';
    const LARGE_COOKIE_VALUE = 'l';
    const SMALL_COOKIE_VALUE = 's';

    /** @var array */
    private $filters = array();
    /** @var string */
    private $requestMethod;
    /** @var string */
    private $uri;
    /** @var array */
    private $uriParts;
    /** @var boolean */
    private $isRestful = false;
    /** @var boolean */
    private $admin;
    /** @var string */
    private $controller;
    /** @var string */
    private $action;
    /** @var int */
    private $id;
    /** @var string */
    private $enhancementVersion;

    /**
     * @PdInject uri
     * @param $uri
     */
    public function setURI($uri)
    {
        $this->uri = $uri;
        $this->parseUriParts();
        $this->resolveDataParts();
    }

    private function parseUriParts()
    {
        //TODO: investigate parse_url...
        $this->uriParts = explode('/', $this->uri);
    }

    private function resolveDataParts()
    {
        foreach ($this->uriParts as $part) {
            if (is_numeric($part)) {
                $this->isRestful = true;
            }
        }

        if ($this->isRestful) {
            $this->resolvePartsFromRestfulUri();
        } else {
            $this->resolvePartsFromNonRestfulUri();
        }
    }

    private function resolvePartsFromNonRestfulUri()
    {
        if ($this->uriParts[1] === 'index.php') {
            array_shift($this->uriParts);
        }

        if ($this->uriParts[1] === 'admin') {
            $this->admin = true;
            array_shift($this->uriParts);
        } else {
            $this->admin = false;
        }

        if (empty($this->uriParts[1])) {
            $this->controller = 'home';
        } else {
            $this->controller = $this->uriParts[1];
        }

        if (empty($this->uriParts[2]) || strpos($this->uriParts[2], '?') === 0) {
            $this->action = 'index';
        } elseif (is_numeric($this->uriParts[2])) {
            $this->action = 'index';
            $this->id = $this->uriParts[2];
        } else {
            $this->action = $this->uriParts[2];
        }

        if (!empty($this->uriParts[3])) {
            $this->setId($this->uriParts[3]);
        }
    }

    private function resolvePartsFromRestfulUri()
    {
        if ($this->uriParts[1] === 'index.php') {
            array_shift($this->uriParts);
        }

        if ($this->uriParts[1] === 'admin') {
            $this->admin = true;
            array_shift($this->uriParts);
        } else {
            $this->admin = false;
        }

        $last = array_pop($this->uriParts);
        if (empty($last)) {
            $last = array_pop($this->uriParts);
        }
        if (is_numeric($last)) {
            $this->setId($last);
            $last = array_pop($this->uriParts);
        }
        $this->controller = $last;
        $this->action = 'index';

        $this->getPathFilters();
    }

    private function getPathFilters()
    {
        $filterId = array_pop($this->uriParts);
        $filter = array_pop($this->uriParts);
        $this->filters[$filter] = $filterId;
    }

    /**
     * @param $id
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param  $method
     * @return void
     * @PdInject request_method
     */
    public function setRequestMethod($method)
    {
        if ($method === self::POST && !empty($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        $this->requestMethod = $method;
    }

    /**
     * @PdInject enhancementVersion
     * @param $version
     * @return void
     */
    public function setEnhancementVersion($version)
    {
        if (empty($version)) {
            $this->enhancementVersion = self::BASE_ENHANCEMENT;
        } else {
            $this->setVersionBasedOnCookieValue($version);
        }
    }

    public function setVersionBasedOnCookieValue($version)
    {
        if (self::LARGE_COOKIE_VALUE === $version) {
            $this->enhancementVersion = self::LARGE_ENHANCEMENT;
        } else {
            $this->enhancementVersion = self::SMALL_ENHANCEMENT;
        }
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getEnhancementVersion()
    {
        return $this->enhancementVersion;
    }

    public function getHost()
    {
        return $_SERVER['HTTP_HOST'];
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function setAction($action)
    {
        $this->action = $action;
    }
}
