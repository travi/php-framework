<?php
/*
 * Created on Dec 14, 2008
 * By Matt Travi
 * programmer@travi.org
 */

require_once(dirname(__FILE__).'/../../../objects/page/xhtml.class.php');
require_once(dirname(__FILE__).'/../../http/Request.class.php');
require_once(dirname(__FILE__).'/../../http/Response.class.php');
require_once(dirname(__FILE__).'/../abstract.controller.php');
require_once(dirname(__FILE__).'/../../exception/NotFound.exception.php');

 
 //This should use the singleton pattern

//$controller = new FrontController;

//$controller->Display();

class FrontController
{
	private $Request;
	private $Response;
	
	private $View;		//Object containing template, css, js, etc information (Is this needed? $Response should handle this information. Maybe it is an attribute of $Response...
	
	public function __construct()
	{
        $this->Request = new Request();
		$this->Response = new Response();
		
		$this->importSiteSettings();
		
		//processRequest();
	}
		
	public function processRequest()
	{
		$this->dispatchToController();
//		sendResponse();
	}

    private function dispatchToController()
    {
        $controllerName = $this->Request->getController();
        $controllerPath = DOC_ROOT.'../app/controller/'.$controllerName.'.controller.php';

        try {
            if(is_file($controllerPath))
            {
                require_once($controllerPath);
                $controller = new $controllerName();
                $controller->doAction($this->Request, $this->Response);
            }
            else
            {
                throw new NotFoundException('Controller Not Found!');
            }
        } catch (NotFoundException $e) {
            echo '<h2>404</h2>';
            echo '<p>' . $e->getMessage() . '</p>'; //TODO: only show this in dev mode, but log it in other environments
        }
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