<?php

use travi\framework\controller\AbstractController,
    travi\framework\http\Request,
    travi\framework\http\Response,
    travi\framework\exception\NotFoundException;

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
            'travi\\framework\\controller\\AbstractController'
        );

        $requestStub = $this->getMock('travi\\framework\\http\\Request');
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

        $this->responseStub = $this->getMock('travi\\framework\\http\\Response');
    }

    /**
     * @expectedException travi\framework\exception\NotFoundException
     */
    public function testDoActionGivesNotFoundException()
    {
        $this->controller->doAction($this->requestStub, $this->responseStub);
    }
}
