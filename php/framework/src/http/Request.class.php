<?php
 
class Request
{
    const GET = 'GET';
    const POST = 'POST';

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

    /**
     * @PdInject uri
     */
    public function setURI($uri)
    {
        $this->uri = $uri;
        $this->parseUriParts();
        $this->resolveDataParts();
    }

    private function parseUriParts()
    {
        $this->uriParts = explode('/', $this->uri);
    }

    private function resolveDataParts()
    {
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
        $this->requestMethod = $method;
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

}
