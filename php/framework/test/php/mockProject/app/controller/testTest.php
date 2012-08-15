<?php
require_once 'PHPUnit/Autoload.php';

require_once 'test.controller.php';
require_once dirname(__FILE__).'/../../../../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../../../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../../../src/http/Response.class.php';

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

        $requestStub = $this->getMock('Request');
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));

        $this->request = $requestStub;
    }

    public function testIndex()
    {
        $responseStub = $this->getMock('Response');
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with('Test');

        $this->controller->index($this->request, $responseStub);
    }
}
?>
