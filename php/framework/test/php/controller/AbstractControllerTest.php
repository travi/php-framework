<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../src/controller/abstract.controller.php';
require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';
require_once dirname(__FILE__).'/../../../src/exception/NotFound.exception.php';

class AbstractControllerTest extends PHPUnit_Framework_TestCase
{
    /** @var AbstractController */
    protected $controller;
    /** @var Request */
    protected $requestStub;
    /** @var Response */
    protected $responseStub;

    protected function setUp()
    {
        $this->controller = $this->getMockForAbstractClass('AbstractController');

        $requestStub = $this->getMock('Request');
        $requestStub->expects($this->any())
            ->method('getController')
            ->will($this->returnValue('test'));
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));
        $this->requestStub = $requestStub;

        $this->responseStub = $this->getMock('Response');
    }

    /**
     * @expectedException NotFoundException
     */
    public function testDoActionGivesNotFoundException()
    {
        $this->controller->doAction($this->requestStub, $this->responseStub);
    }
}
