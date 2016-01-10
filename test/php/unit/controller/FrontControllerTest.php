<?php

require_once __DIR__ . '/../../../mockProject/app/controller/test.controller.php';

use travi\framework\auth\Authentication;
use travi\framework\controller\front\FrontController,
    travi\framework\controller\ErrorController,
    travi\framework\utilities\FileSystem;
use travi\framework\exception\UnauthorizedException;

class FrontControllerTest extends PHPUnit_Framework_TestCase
{
    private $request;
    private $pathToDocRoot = '/path/to/doc/root/';
    private $controllerName = 'test';
    /** @var FrontController */
    protected $frontController;
    /** @var FileSystem */
    private $fileSystem;

    public function setUp()
    {
        $this->frontController = new FrontControllerShunt();

        $config = array('docRoot' => $this->pathToDocRoot);

        $this->frontController->setConfig($config);
        $this->frontController->setErrorController(new ErrorController());

        $this->fileSystem = $this->getMock('travi\\framework\\utilities\\FileSystem');
        $this->request = $this->getMock('travi\\framework\\http\\Request');

        $this->frontController->setFileSystem($this->fileSystem);
        $this->frontController->setRequest($this->request);

    }

    public function testProcessRequest()
    {
        $this->fileSystem->expects($this->once())
            ->method('fileExists')
            ->with($this->pathToDocRoot . '../app/controller/' . $this->controllerName . '.controller.php')
            ->will($this->returnValue(true));

        $mockRequest = $this->getMock('travi\\framework\\http\\Request');
        $mockRequest->expects($this->any())
            ->method('isAdmin')
            ->will($this->returnValue(false));
        $mockRequest->expects($this->once())
            ->method('getController')
            ->will($this->returnValue($this->controllerName));

        //in created controller
        $mockRequest->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $mockResponse = $this->getMock('travi\\framework\\http\\Response');
        $mockResponse->expects($this->once())
            ->method('format');
        $mockResponse->expects($this->once())
            ->method('setContent')
            ->with(
                $this->equalTo(
                    array(
                        'key1' => 'someContent'
                    )
                )
            );

        //in created controller
        $mockResponse->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Test'));

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($mockResponse);

        $this->frontController->processRequest();
    }



    public function testGetAdminController()
    {
        /** @var $authentication Authentication */
        $authentication = $this->getMock('travi\\framework\\auth\\Authentication');
        $this->frontController->setAuthentication($authentication);
        $authentication->expects($this->once())
            ->method('ensureAuthenticated');

        $this->fileSystem->expects($this->once())
            ->method('fileExists')
            ->with(
                $this->pathToDocRoot .
                '../app/controller/admin/' .
                $this->controllerName .
                '.controller.php'
            )
            ->will($this->returnValue(true));

        $mockRequest = $this->getMock('travi\\framework\\http\\Request');
        $mockRequest->expects($this->any())
            ->method('isAdmin')
            ->will($this->returnValue(true));
        $mockRequest->expects($this->once())
            ->method('getController')
            ->will($this->returnValue($this->controllerName));

        //in created controller
        $mockRequest->expects($this->once())
            ->method('getAction');

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($this->getMock('travi\\framework\\http\\Response'));

        $this->frontController->processRequest();
    }

    public function testSetContentOnlyCalledIfContentReturned()
    {
        $this->fileSystem->expects($this->once())
            ->method('fileExists')
            ->with($this->pathToDocRoot . '../app/controller/' . $this->controllerName . '.controller.php')
            ->will($this->returnValue(true));

        $mockRequest = $this->getMock('travi\\framework\\http\\Request');
        $mockRequest->expects($this->any())
            ->method('isAdmin')
            ->will($this->returnValue(false));
        $mockRequest->expects($this->once())
            ->method('getController')
            ->will($this->returnValue($this->controllerName));

        //in created controller
        $mockRequest->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('noContentToReturn'));

        $mockResponse = $this->getMock('travi\\framework\\http\\Response');
        $mockResponse->expects($this->never())
            ->method('setContent');

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($mockResponse);

        $this->frontController->processRequest();
    }

    public function test404()
    {
        $this->request->expects($this->once())
            ->method('getController')
            ->will($this->returnValue('nonExistantPage'));

        $mockResponse = $this->getMock('travi\\framework\\http\\Response');
        $mockResponse->expects($this->once())
            ->method('format');

        $errorController = $this->getMock('travi\\framework\\controller\\ErrorController');
        $errorController->expects($this->once())
            ->method('doAction')
            ->with(
                $this->identicalTo($this->request),
                $this->identicalTo($mockResponse),
                'error404'
            );

        $this->frontController->setResponse($mockResponse);
        $this->frontController->setErrorController($errorController);

        $this->frontController->processRequest();
    }

    public function test500()
    {
        $this->fileSystem->expects($this->once())
            ->method('fileExists')
            ->with($this->pathToDocRoot . '../app/controller/' . $this->controllerName . '.controller.php')
            ->will($this->returnValue(true));

        $this->request->expects($this->any())
            ->method('getController')
            ->will($this->returnValue($this->controllerName));

        //in created controller
        $this->request->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('throwsError'));

        $mockResponse = $this->getMock('travi\\framework\\http\\Response');
        $mockResponse->expects($this->once())
            ->method('format');

        $errorController = $this->getMock('travi\\framework\\controller\\ErrorController');
        $errorController->expects($this->once())
            ->method('doAction')
            ->with(
                $this->identicalTo($this->request),
                $this->identicalTo($mockResponse),
                'error500'
            );

        $this->frontController->setResponse($mockResponse);
        $this->frontController->setErrorController($errorController);

        $this->frontController->processRequest();
    }

    public function testUserPromptedForCredentialsWhenAdminIfNotAlreadyAuthenticated()
    {
        /** @var $authentication Authentication */
        $authentication = $this->getMock('travi\\framework\\auth\\Authentication');
        $this->frontController->setAuthentication($authentication);
        $authentication->expects($this->once())
            ->method('ensureAuthenticated')
            ->will($this->throwException(new UnauthorizedException()));

        $mockRequest = $this->getMock('travi\\framework\\http\\Request');
        $mockRequest->expects($this->any())
            ->method('isAdmin')
            ->will($this->returnValue(true));

        $errorController = $this->getMock('travi\\framework\\controller\\ErrorController');
        $this->frontController->setErrorController($errorController);
        $errorController->expects($this->once())
            ->method('doAction');

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($this->getMock('travi\\framework\\http\\Response'));

        $this->frontController->processRequest();
    }
}

class FrontControllerShunt extends FrontController
{
    protected function getController($controllerName)
    {
        return new Test();
    }
}
