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
//
////TODO: temp work around
//$config = array();
//$config['debug'] = true;
 
 //This should use the singleton pattern

//$controller = new FrontController;

//$controller->Display();

class FrontController
{
	private $Request;
	private $Response;

    private $config;
	
//	public function __construct()
//	{
////		$this->importSiteSettings();
////
////        $this->Request = new Request();
////		$this->Response = new Response($this->config);
//
//
//
//		//processRequest();
//	}

    /**
     * @PdInject config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @PdInject request
     */
    public function setRequest($request)
    {
        $this->Request = $request;
    }

    /**
     * @PdInject response
     */
    public function setResponse($response)
    {
        $this->Response = $response;
    }
		
	public function processRequest()
	{
		$this->dispatchToController();
		$this->sendResponse();
	}

    private function dispatchToController()
    {
        $controllerName = $this->Request->getController();
        $controllerPath = $this->config['docRoot'] . '../app/controller/'.$controllerName.'.controller.php';

        echo 'Controller Path: ' . realpath($controllerPath) . "\n";

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
            //TODO: Use actual 404 status code and hand off to custom error page
            //TODO: also link to contact page...
            echo '<h2>404</h2>';
            echo '<p>' . $e->getMessage() . '</p>';//TODO: only show this in dev mode, but log it in other environments

        } catch (Exception $e) {
            echo '<h2>Exception: ' . $e . '</h2>';
        }
    }
 
	private function sendResponse()
	{
		$this->Response->respond();
	}

    private function importSiteSettings()
    {
        //Temp definition
        define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
        define('SITE_ROOT', DOC_ROOT.'../');
        require_once(dirname(__FILE__).'/../../../../thirdparty/spyc/spyc.php');

        $this->config = Spyc::YAMLLoad(SITE_ROOT.'config/siteConfig.yaml');
    }
}
?>