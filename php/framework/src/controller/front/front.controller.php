<?php
/*
 * Created on Dec 14, 2008
 * By Matt Travi
 * programmer@travi.org
 */

require_once(dirname(__FILE__).'/../../../objects/page/xhtml.class.php');
require_once('../src/response/Response.class.php');
require_once(dirname(__FILE__).'/../abstract.controller.php');

 
 //This should use the singleton pattern

//$controller = new FrontController;

//$controller->Display();

class FrontController
{
	private $controller;
//	private $page;
//	private $id;
	private $uriParts;
    private $admin;     //boolean

    //these may make more sense to be attributes of the individual controller
	private $Request;	//Object for abstracting uri processes, and variables like browser etc
	private $Response;	//Object for building the list of content that will be sent as the response
	
	private $View;		//Object containing template, css, js, etc information (Is this needed? $Response should handle this information. Maybe it is an attribute of $Response...
	
	public function __construct()
	{
		$this->Response = new Response();

        $this->parseUriParts();
        $this->resolveDataParts();
		
		$this->importSiteSettings();
		
		//processRequest();
	}
		
	public function processRequest()
	{
		$this->forwardToController();
//		sendResponse();
	}

    //Dispatch
    private function forwardToController()
    {
        $controllerName = $this->controller;
        require_once(DOC_ROOT.'../app/controller/'.$controllerName.'.controller.php');
        $controller = new $controllerName($this->uriParts);
        $controller->doAction($this->Request, $this->Response);
    }
	
	/*private function getApplications()	Part of forward to controller process
	{
		
	}
	
	private function getPages($application)
	{
		
	}*/
	
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
    }

    private function requestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
 
	public function sendResponse()
	{
		//setMimeType based on type set in Response object (html, html fragment, xml, json)
		if($_SERVER['X-Requested-With'] == 'XMLHttpRequest')
		{
			echo $this->content;
		}
		else
		{
			//echo $this->uri;
		/*	if(!isset($this->smartyConfig))
				$this->getSmartyConfig();
	
	        require_once($this->smartyConfig['pathToSmarty']);
				
			uksort($this->stylesheets, 'strnatcasecmp');
	
			$smarty = new Smarty();
	
			$smarty->template_dir = $this->smartyConfig['smartyTemplateDir'];
			$smarty->compile_dir = $this->smartyConfig['smartyCompileDir'];
			$smarty->cache_dir = $this->smartyConfig['smartyCacheDir'];
			$smarty->config_dir = $this->smartyConfig['smartyConfigDir'];
	
			if($this->debug)
			{
				$smarty->force_compile = true;
			}
			else
			{
				$smarty->compile_check = false;
			}
	
			$smarty->assign('page',$this);
			$smarty->display($this->smartyTemplate);*/
		}
	}

    private function importSiteSettings()
    {
        //Temp definition
        define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
    }
}
?>