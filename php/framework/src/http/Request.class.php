<?php
 
class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const DELETE = 'DELETE';

    const ENHANCEMENT_VERSION_KEY = 'enhancementVersion';
    const BASE_ENHANCEMENT = 'base';
    const MOBILE_ENHANCEMENT = 'mobile';
    const DESKTOP_ENHANCEMENT = 'desktop';

    /** @var string */
    private $requestMethod;
    /** @var string */
    private $uri;
    /** @var array */
    private $uriParts;
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
            $this->enhancementVersion = $version;
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

}
