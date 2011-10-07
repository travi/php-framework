<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';
require_once dirname(__FILE__).'/../../../src/exception/NotFound.exception.php';
require_once dirname(__FILE__).'/../../../src/controller/error.controller.php';

class ErrorControllerTest extends PHPUnit_Framework_TestCase
{
    /** @var ErrorController */
    protected $controller;
    /** @var Request */
    protected $request;
    /** @var Response */
    private $response;

    protected function setUp()
    {
        $this->controller = new ErrorController;

        $requestStub = $this->getMock('Request');
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $responseStub = $this->getMock('Response');

        $this->request = $requestStub;
        $this->response = $responseStub;
    }

    public function testError404()
    {
        $errorMessage = 'unitTest';

        $this->response->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Page Could Not Be Found'));
        $this->response->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/404.tpl'));
        $this->response->expects($this->once())
            ->method('addToResponse')
            ->with(
                $this->equalTo('errorMessage'),
                $this->equalTo($errorMessage)
            );

        $this->controller->error404($this->request, $this->response, new NotFoundException($errorMessage));
    }

    public function testError500()
    {
        $errorMessage = 'unitTest';

        $this->response->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Internal Server Error'));
        $this->response->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/500.tpl'));
        $this->response->expects($this->at(2))
            ->method('addToResponse')
            ->with(
                $this->equalTo('message'),
                $this->equalTo($errorMessage)
            );
        $this->response->expects($this->at(3))
            ->method('addToResponse')
            ->with(
                $this->equalTo('trace')
            );

        $this->controller->error500($this->request, $this->response, new NotFoundException($errorMessage));
    }

    public function testError401()
    {
        $this->response->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('You are not authorized to view this page'));
        $this->response->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/401.tpl'));

        $this->controller->error401($this->request, $this->response, new NotFoundException($errorMessage));
    }
}
