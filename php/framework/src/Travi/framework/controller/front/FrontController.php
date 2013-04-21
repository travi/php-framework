<?php

namespace Travi\framework\controller\front;

use Travi\framework\auth\Authentication;
use Travi\framework\exception\UnauthorizedException,
    Travi\framework\http\Response,
    Travi\framework\http\Request,
    Travi\framework\exception\NotFoundException,
    Travi\framework\controller\AbstractController,
    Travi\framework\controller\ErrorController,
    Travi\framework\utilities\FileSystem;

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

        if (!isset($_SERVER['PHP_AUTH_USER'])
            && !isset($_SERVER['PHP_AUTH_PW'])
            && isset($_SERVER['HTTP_AUTHORIZATION'])
        ) {
            list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
                = explode(':', base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
        }

        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
            $authenticated = $this->authenticate($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        }

        if (!$authenticated) {
            throw new UnauthorizedException();
        }
    }

    private function promptForCredentials()
    {
        header('WWW-Authenticate: Basic realm="Travi Admin"');

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
     * @PdInject new:Travi\framework\controller\ErrorController
     */
    public function setErrorController($controller)
    {
        $this->errorController = $controller;
    }

    /**
     * @param $env FileSystem
     * @PdInject new:Travi\framework\utilities\FileSystem
     */
    public function setFileSystem($env)
    {
        $this->fileSystem = $env;
    }

    /**
     * @param $authentication Authentication
     * @PdInject new:Travi\framework\auth\Authentication
     */
    public function setAuthentication($authentication)
    {
        $this->authentication = $authentication;
    }
}