<?php

namespace travi\framework\controller\front;

use travi\framework\auth\Authentication;
use travi\framework\exception\UnauthorizedException,
    travi\framework\http\Response,
    travi\framework\http\Request,
    travi\framework\exception\NotFoundException,
    travi\framework\controller\AbstractController,
    travi\framework\controller\ErrorController,
    travi\framework\utilities\FileSystem;

class FrontController
{
    /** @var Authentication */
    public $authentication;
    /** @var Request */
    private $request;
    /** @var Response */
    private $response;
    /** @var ErrorController */
    private $errorController;
    /** @var FileSystem */
    private $fileSystem;

    private $config;

    public function processRequest()
    {
        try{

            if ($this->request->isAdmin()) {
                $this->authentication->ensureAuthenticated();
            }
            $this->dispatchToController();
            $this->sendResponse();
        } catch (UnauthorizedException $e) {
            $this->promptForCredentials();
        } catch (NotFoundException $e) {
            $this->respondWithError(404, $e);
        } catch (\Exception $e) {
            //TODO: can this be done in a way that PhpUnit exceptions don't get caught?
            $this->respondWithError(500, $e);
        }
    }

    private function dispatchToController()
    {
        $extraPathParts = ($this->request->isAdmin()) ? 'admin/' : '';

        $controllerName = $this->request->getController();
        $controllerPath = $this->config['docRoot'] . '../app/controller/'
                          . $extraPathParts
                          . $controllerName . '.controller.php';

        if ($this->controllerExists($controllerPath)) {
            /** @var $controller AbstractController */
            $controller = $this->getController($controllerName, $controllerPath, $this->request->isAdmin());

            $modelMap = $controller->doAction($this->request, $this->response);

            if (!empty($modelMap)) {
                $this->response->setContent($modelMap);
            }
        } else {
            throw new NotFoundException($controllerName . ' Controller Not Found!');
        }
    }

    private function controllerExists($controllerPath)
    {
        return $this->fileSystem->fileExists($controllerPath);
    }

    protected function getController($controllerName, $controllerPath, $isAdmin)
    {
        include_once $controllerPath;

        $fromContext = \Pd_Container::get()->dependencies()->get($controllerName . '-controller');

        if (isset($fromContext)) {
            return $fromContext;
        } else {
            if ($isAdmin) {
                $controllerName .= 'Admin';
            }
            return \Pd_Make::name($controllerName);
        }
    }

    private function sendResponse()
    {
        $this->response->format();
    }

    /**
     * @param $errorCode
     * @param null $exception Exception
     */
    private function respondWithError($errorCode, $exception = null)
    {
        $this->errorController->doAction(
            $this->request,
            $this->response,
            'error' . $errorCode,
            $exception
        );
        $this->sendResponse();
    }

    private function promptForCredentials()
    {
        //Show Unauthorized page if user chooses cancel
        $this->respondWithError(401);
    }

    /**
     * @PdInject config
     * @param $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    /**
     * @PdInject request
     * @param $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @PdInject response
     * @param $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * @param $controller
     * @PdInject new:travi\framework\controller\ErrorController
     */
    public function setErrorController($controller)
    {
        $this->errorController = $controller;
    }

    /**
     * @param $env FileSystem
     * @PdInject fileSystem
     */
    public function setFileSystem($env)
    {
        $this->fileSystem = $env;
    }

    /**
     * @param $authentication Authentication
     * @PdInject new:travi\framework\auth\Authentication
     */
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }
}