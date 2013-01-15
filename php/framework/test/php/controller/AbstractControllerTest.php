<?php

use Travi\framework\controller\AbstractController,
    Travi\framework\http\Request,
    Travi\framework\http\Response,
    Travi\framework\exception\NotFoundException;

class AbstractControllerTest extends PHPUnit_Framework_TestCase
{
    private $filters = array('filter' => 1234);
    /** @var AbstractController */
    protected $controller;
    /** @var Request */
    protected $requestStub;
    /** @var Response */
    protected $responseStub;

    protected function setUp()
    {
        $this->controller = $this->getMockForAbstractClass(
            'Travi\\framework\\controller\\AbstractController'
        );

        $requestStub = $this->getMock('Travi\\framework\\http\\Request');
        $requestStub->expects($this->any())
            ->method('getController')
            ->will($this->returnValue('test'));
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));
        $requestStub->expects($this->once())
            ->method('getFilters')
            ->will($this->returnValue($this->filters));
        $this->requestStub = $requestStub;

        $this->responseStub = $this->getMock('Travi\\framework\\http\\Response');
    }

    /**
     * @expectedException Travi\framework\exception\NotFoundException
     */
    public function testDoActionGivesNotFoundException()
    {
        $this->controller->doAction($this->requestStub, $this->responseStub);
    }
}
