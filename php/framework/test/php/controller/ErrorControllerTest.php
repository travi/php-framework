<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';
require_once dirname(__FILE__).'/../../../src/exception/NotFound.exception.php';
require_once dirname(__FILE__).'/../../../src/controller/error.controller.php';

/**
 * Test class for ErrorController.
 * Generated by PHPUnit on 2011-01-15 at 18:12:07.
 */
class ErrorControllerTest extends PHPUnit_Framework_TestCase
{
    /** @var ErrorController */
    protected $controller;
    /** @var Request */
    protected $request;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->controller = new ErrorController;

        $requestStub = $this->getMock('Request');
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $this->request = $requestStub;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testError404()
    {
        $errorMessage = 'unitTest';

        $responseStub = $this->getMock('Response');
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Page Could Not Be Found'));
        $responseStub->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/404.tpl'));
        $responseStub->expects($this->once())
            ->method('addToResponse')
            ->with(
                $this->equalTo('errorMessage'),
                $this->equalTo($errorMessage)
            );

        $this->controller->error404($this->request, $responseStub, new NotFoundException($errorMessage));
    }

    public function testError500()
    {
        $errorMessage = 'unitTest';

        $responseStub = $this->getMock('Response');
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Internal Server Error'));
        $responseStub->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/500.tpl'));
        $responseStub->expects($this->once())
            ->method('addToResponse')
            ->with(
                $this->equalTo('message'),
                $this->equalTo($errorMessage)
            );

        $this->controller->error500($this->request, $responseStub, new NotFoundException($errorMessage));
    }
}
