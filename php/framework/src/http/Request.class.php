<?php
/**
 * User: travi
 * Date: Jan 2, 2011
 * Time: 12:38:52 PM
 */
 
class Request
{
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
        if($this->uriParts[1] === 'admin')
        {
            $this->admin = true;
            array_shift($this->uriParts);
        }
        else
        {
            $this->admin = false;
        }

        if(empty($this->uriParts[1]))
        {
            $this->controller = 'home';
        }
        else
        {
            $this->controller = $this->uriParts[1];
        }

        if(empty($this->uriParts[2]))
        {
            $this->action = 'index';
        }
        else
        {
            $this->action = $this->uriParts[2];
        }
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
}
