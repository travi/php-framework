<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/controller/crud.controller.php';
require_once dirname(__FILE__).'/../../../src/http/Request.class.php';

class CrudControllerTest extends PHPUnit_Framework_TestCase
{
    /** @var CrudController */
    private $controller;

    private $response;

    public function testGetListRoutesToProperMethod()
    {
        $this->response = new Response(array());

        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->once())
            ->method('getRequestMethod');

        $this->controller = new CrudController();

        $this->controller->index($mockRequest, $this->response);
    }

    public function testGetByIdRoutesToProperMethod()
    {
        $this->response = new Response(array());

        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->once())
            ->method('getRequestMethod');
        $mockRequest->expects($this->once())
            ->method('getId');

        $this->controller = new CrudController();

        $this->controller->index($mockRequest, $this->response);
    }
}