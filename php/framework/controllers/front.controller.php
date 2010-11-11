<?php
/*
 * Created on Dec 14, 2008
 * By Matt Travi
 * programmer@travi.org
 */
//Dispatcher
 
 //This should use the singleton pattern

//$controller = new FrontController;

//$controller->Display();

class FrontController
{
	/*private $application;		part of Request object
	private $page;
	private $id;
	private $uri;*/

    //these may make more sense to be attributes of the individual controller
	private $Request;	//Object for abstracting uri processes, and variables like browser etc
	private $Response;	//Object for building the list of content that will be sent as the response
	
	private $View;		//Object containining template, css, js, etc information
	
	public function __construct()
	{
		//$this->parseUri();
		$this->uriParts();
		
		//importSiteSettings();
		
		//processRequest();
	}
		
	public function processRequest()
	{
		forwardToController();
		sendResponse();
	}
	
	/*private function getApplications()	Part of forward to controller process
	{
		
	}
	
	private function getPages($application)
	{
		
	}*/
	
	/*private function parseUri()	Part of request object
	{
		$this->uri = parse_url();
	}*/
	
	private function uriParts()
	{
		$navString = $_SERVER['REQUEST_URI'];
		$parts = explode('/', $navString); // Break into an array
		// Lets look at the array of items we have:
		print_r($parts);
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
}
?>