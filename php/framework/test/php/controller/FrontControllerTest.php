<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/controller/front/front.controller.php';
require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';

class FrontControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FrontController
     */
    protected $frontController;

    public function setUp()
    {
        $this->frontController = new FrontControllerShunt();

        $config = array('docRoot' => dirname(__FILE__) . '/../mockProject/doc_root/');

        $this->frontController->setConfig($config);
    }

    public function testProcessRequest()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->any())
            ->method('isAdmin')
            ->will($this->returnValue(false));
        $mockRequest->expects($this->once())
            ->method('getController')
            ->will($this->returnValue('test'));

        //in created controller
        $mockRequest->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $mockResponse = $this->getMock('Response');
        $mockResponse->expects($this->once())
            ->method('format');

        //in created controller
        $mockResponse->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Test'));

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($mockResponse);

        $this->frontController->processRequest();
    }

    public function test404()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->once())
            ->method('getController')
            ->will($this->returnValue('nonExistantPage'));

        $mockResponse = $this->getMock('Response');
        $mockResponse->expects($this->once())
            ->method('format');

        $errorController = $this->getMock('ErrorController');
        $errorController->expects($this->once())
            ->method('doAction')
            ->with(
                $mockRequest,
                $mockResponse,
                'error404'
            );

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($mockResponse);
        $this->frontController->setErrorController($errorController);

        $this->frontController->processRequest();
    }

    public function test500()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->any())
                ->method('getController')
                ->will($this->returnValue('test'));

        //in created controller
        $mockRequest->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue('throwsError'));

        $mockResponse = $this->getMock('Response');
        $mockResponse->expects($this->once())
            ->method('format');

        $errorController = $this->getMock('ErrorController');
        $errorController->expects($this->once())
            ->method('doAction')
            ->with(
                $mockRequest,
                $mockResponse,
                'error500'
            );

        $this->frontController->setRequest($mockRequest);
        $this->frontController->setResponse($mockResponse);
        $this->frontController->setErrorController($errorController);

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
