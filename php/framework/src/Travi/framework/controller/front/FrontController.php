<?php

namespace Travi\framework\controller\front;

use Travi\framework\http\Response,
    Travi\framework\http\Request,
    Travi\framework\exception\NotFoundException,
    Travi\framework\controller\AbstractController,
    Travi\framework\controller\ErrorController;

class FrontController
{
    /** @var $Request Request */
    private $Request;
    /** @var $Response Response */
    private $Response;

    private $config;

    public function processRequest()
    {
        if ($this->Request->isAdmin()) {
            try {
                $this->ensureUserIsAuthenticated();
            } catch (\Exception $e) {
                $this->respondWithError(500, $e);
            }
        }

        try{
            $this->dispatchToController();
            $this->sendResponse();
        } catch (NotFoundException $e) {
            $this->respondWithError(404, $e);
        } catch (\Exception $e) {
            //TODO: can this be done in a way that PhpUnit exceptions dont get caught?
            $this->respondWithError(500, $e);
        }
    }

    private function dispatchToController()
    {
        $extraPathParts = ($this->Request->isAdmin()) ? 'admin/' : '';

        $controllerName = $this->Request->getController();
        $controllerPath = $this->config['docRoot'] . '../app/controller/'
                          . $extraPathParts
                          . $controllerName . '.controller.php';

        if (is_file($controllerPath)) {
            include_once $controllerPath;

            /** @var $controller AbstractController */
            $controller = $this->getController($controllerName);

            $modelMap = $controller->doAction($this->Request, $this->Response);

            if (!empty($modelMap)) {
                $this->Response->setContent($modelMap);
            }
        } else {
            throw new NotFoundException($controllerName . ' Controller Not Found!');
        }
    }

    protected function getController($controllerName)
    {
        $controller = \Pd_Make::name($controllerName);
        return $controller;
    }

    private function sendResponse()
    {
        $this->Response->format();
    }

    private function respondWithError($errorCode, $exception = null)
    {
        $this->errorController->doAction(
            $this->Request,
            $this->Response,
            'error' . $errorCode,
            $exception
        );
        $this->sendResponse();
    }

    private function authenticate($user, $pass)
    {
        $pwFile = SITE_ROOT . 'config/auth/.pwd';

        if (file_exists($pwFile) && is_readable($pwFile)) {
            if ($pwFileHandle = fopen($pwFile, 'r')) {
                while ($line = fgets($pwFileHandle)) {
                    //remove line endings
                    $line = preg_replace('`[\r\n]$`', '', $line);
                    list($validUser, $validPass) = explode(':', $line);
                    if ($validUser === $user) {
                        //the salt is the first to characters for DES encryption
                        $salt = substr($validPass, 0, 2);
                        $encryptedPassword = crypt($pass, $salt);

                        if ($validPass === $encryptedPassword) {
                            fclose($pwFileHandle);
                            return true;
                        } else {
                            fclose($pwFileHandle);
                            return false;
                        }
                    }
                }
                fclose($pwFileHandle);
            } else {
                throw new \Exception("couldn't open password file");
            }
        } else {
            throw new \Exception("password file doesn't exist or is not readable");
        }
    }

    public function ensureUserIsAuthenticated()
    {
        //TODO: move this process out to its own class and get this stuff tested
        // probably also need some file loader to make it testable

        $authenticated = false;

        if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
            list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
                = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $authenticated = $this->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        }

        if (!$authenticated) {
            header('WWW-Authenticate: Basic realm="Travi Admin"');

            //Show Unauthorized page if user chooses cancel
            $this->respondWithError(401);
        }
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
        $this->Request = $request;
    }

    /**
     * @PdInject response
     * @param $response
     */
    public function setResponse($response)
    {
        $this->Response = $response;
    }

    /**
     * @param $controller
     * @PdInject new:Travi\framework\controller\ErrorController
     */
    public function setErrorController($controller)
    {
        $this->errorController = $controller;
    }
}