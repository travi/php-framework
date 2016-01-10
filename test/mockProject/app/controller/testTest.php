<?php
require_once 'test.controller.php';

use travi\framework\http\Request,
    travi\framework\http\Response;

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

        $requestStub = $this->getMock('travi\\framework\\http\\Request');
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $this->request = $requestStub;
    }

    public function testIndex()
    {
        $responseStub = $this->getMock('travi\\framework\\http\\Response');
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with('Test');

        $this->controller->index($this->request, $responseStub);
    }
}
