<?php

require_once dirname(__FILE__) . '/../../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../abstract.controller.php';
require_once dirname(__FILE__).'/../../exception/NotFound.exception.php';

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
        if ($this->Request->isAdmin()) {
            try {
                $this->ensureUserIsAuthenticated();
            } catch (Exception $e) {
                $this->respondWithError(500, $e);
            }
        }
        $this->dispatchToController();
        $this->sendResponse();

        return $this->Response;
    }



    private function dispatchToController()
    {
        $extraPathParts = ($this->Request->isAdmin()) ? 'admin/' : '';

        $controllerName = $this->Request->getController();
        $controllerPath = $this->config['docRoot'] . '../app/controller/'
                          . $extraPathParts
                          . $controllerName . '.controller.php';

        try {
            if (is_file($controllerPath)) {
                include_once $controllerPath;

                /** @var $controller AbstractController */
                $controller = Pd_Make::name($controllerName);

                $controller->doAction($this->Request, $this->Response);
            } else {
                throw new NotFoundException('Controller Not Found!');
            }
        } catch (NotFoundException $e) {
            $this->respondWithError(404, $e);
        } catch (Exception $e) {
            $this->respondWithError(500, $e);
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

        $pageStyle = $this->Response->getPageStyle();
        $styleSheetByConvention = '/resources/css/pages/'
                    . $this->Request->getController() . '/'
                    . $this->Request->getAction()
                    . '.css';

        if (empty($template)
            && file_exists($this->config['sitePath'] . '/app/view/pages' . $templateByConvention)
        ) {
            $this->Response->setPageTemplate($templateByConvention);
        }

        if (empty($pageStyle)
            && file_exists($this->config['sitePath'] . '/doc_root' . $styleSheetByConvention)
        ) {
            $this->Response->setPageStyle($styleSheetByConvention);
        }
        //TODO: this should be moved out to the head template once the other sites support mobile
        $this->Response->addMetaTag(
            '<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">'
        );
        $this->Response->respond();
    }

    private function respondWithError($errorCode, $exception = null)
    {
        include_once dirname(__FILE__) . '/../error.controller.php';
        $error = new ErrorController();
        $error->doAction($this->Request, $this->Response, 'error' . $errorCode, $exception);
        $this->sendResponse();
        exit;
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
                throw new Exception("couldn't open password file");
            }
        } else {
            throw new Exception("password file doesn't exist or is not readable");
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
}