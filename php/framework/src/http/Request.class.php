<?php
/**
 * User: travi
 * Date: Jan 2, 2011
 * Time: 12:38:52 PM
 */
 
class Request
{
    private $requestMethod;
	private $uriParts;
    private $admin;     //boolean
	private $controller;
    private $action;

    public function __construct()
    {
        $this->parseUriParts();
        $this->resolveDataParts();
    }

	private function parseUriParts()
	{
		$navString = $_SERVER['REQUEST_URI'];
		$parts = explode('/', $navString);

		$this->uriParts = $parts;
	}

    private function resolveDataParts()
    {
        if($this->uriParts[1] === 'admin')
        {
            $this->admin = true;
            $this->controller = $this->uriParts[2];
            //trim the first item so positions align?
        }
        else
        {
            $this->admin = false;
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

    private function setRequestMethod()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    public function getRequestMethod()
    {
        if(!isset($this->requestMethod))
        {
            $this->setRequestMethod();
        }

        return $this->requestMethod;
    }

}
