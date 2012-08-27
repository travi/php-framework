<?php
require_once 'test.controller.php';

use Travi\framework\http\Request,
    Travi\framework\http\Response;

class TestTest extends PHPUnit_Framework_TestCase
{
    /** @var test */
    protected $controller;
    /** @var Request */
    protected $request;
    /** @var Response */
    protected $response;

    protected function setUp()
    {
        $this->controller = new test;

        $requestStub = $this->getMock('Travi\\framework\\http\\Request');
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $this->request = $requestStub;
    }

    public function testIndex()
    {
        $responseStub = $this->getMock('Travi\\framework\\http\\Response');
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with('Test');

        $this->controller->index($this->request, $responseStub);
    }
}
