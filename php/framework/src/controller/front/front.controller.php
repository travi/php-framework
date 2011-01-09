<?php
/*
 * Created on Dec 14, 2008
 * By Matt Travi
 * programmer@travi.org
 */

require_once(dirname(__FILE__).'/../../../objects/page/xhtml.class.php');
require_once(dirname(__FILE__).'/../abstract.controller.php');
require_once(dirname(__FILE__).'/../../exception/NotFound.exception.php');

class FrontController
{
    /** @var $Request Request */
	private $Request;
    /** @var $Response Response */
	private $Response;

    private $config;

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

        return $this->Response;
	}

    private function dispatchToController()
    {
        $controllerName = $this->Request->getController();
        $controllerPath = $this->config['docRoot'] . '../app/controller/'.$controllerName.'.controller.php';

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
            //TODO: also include link to contact page...
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
}
?>