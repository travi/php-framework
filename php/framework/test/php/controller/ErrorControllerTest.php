<?php

use travi\framework\exception\NotFoundException,
    travi\framework\http\Request,
    travi\framework\http\Response,
    travi\framework\controller\ErrorController;

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

        $requestStub = $this->getMock('travi\\framework\\http\\Request');
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $responseStub = $this->getMock('travi\\framework\\http\\Response');

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
        $this->response->expects($this->once())
            ->method('setStatus')
            ->with(404);

        $this->controller->error404(
            $this->request,
            $this->response,
            null,
            new NotFoundException($errorMessage)
        );
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
        $this->response->expects($this->at(3))
            ->method('addToResponse')
            ->with(
                $this->equalTo('type'),
                $this->equalTo('travi\\framework\\exception\\NotFoundException')
            );
        $this->response->expects($this->at(4))
            ->method('addToResponse')
            ->with(
                $this->equalTo('errorMessage'),
                $this->equalTo($errorMessage)
            );
        $this->response->expects($this->at(5))
            ->method('addToResponse')
            ->with(
                $this->equalTo('trace')
            );
        $this->response->expects($this->once())
            ->method('setStatus')
            ->with(500);

        $this->controller->error500(
            $this->request,
            $this->response,
            null,
            new NotFoundException($errorMessage)
        );
    }

    public function testError401()
    {
        $this->response->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('You are not authorized to view this page'));
        $this->response->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/401.tpl'));
        $this->response->expects($this->once())
            ->method('setStatus')
            ->with(401);

        $this->controller->error401($this->request, $this->response, new NotFoundException($errorMessage));
    }
}
