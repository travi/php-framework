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
            if (is_file($controllerPath)) {
                require_once($controllerPath);
                $controller = new $controllerName();
                $controller->doAction($this->Request, $this->Response);
            } else {
                throw new NotFoundException('Controller Not Found!');
            }
        } catch (NotFoundException $e) {
            require_once(dirname(__FILE__).'/../error.controller.php');
            $error = new ErrorController();
            $error->error404($this->Request, $this->Response, $e);
        } catch (Exception $e) {
            require_once(dirname(__FILE__).'/../error.controller.php');
            $error = new ErrorController();
            $error->error500($this->Request, $this->Response, $e);
        }
    }
 
	private function sendResponse()
	{
        /**
         * TODO: this feels like the wrong place for this
         * should it go in the doAction of the abstract controller?
         * or the Response object?
         */
        $template = $this->Response->getPageTemplate();
        $templateByConvention = '/'
                    . $this->Request->getController() . '/'
                    . $this->Request->getAction()
                    . '.tpl';
        
        if (empty($template)
                && file_exists($this->config['sitePath']
                        . '/app/view/pages' . $templateByConvention)
        ) {
            $this->Response->setPageTemplate($templateByConvention);
        }
        //TODO: this should be moved out to the head template once the other sites support mobile
        $this->Response->addMetaTag('<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">');
		$this->Response->respond();
	}
}
?>