<?php

use travi\framework\http\Request;
use travi\framework\http\Response;

class RestControllerTest extends PHPUnit_Framework_TestCase
{
    const ANY_ID = 42;

    private $abstractMock;
    private $partiallyMockedController;
    private $response;
    private $mockRequest;


    public function setUp()
    {
        $this->abstractMock = $this->getMockForAbstractClass(
            'travi\\framework\\controller\\RestController',
            array(),
            '',
            false,
            false,
            true,
            array()
        );
        $this->partiallyMockedController = $this->getMockForAbstractClass(
            'travi\\framework\\controller\\RestController',
            array(),
            '',
            false,
            false,
            true,
            array(
                'getById'
            )
        );
        $this->response = new Response(array());
        $this->mockRequest = $this->getMock('travi\\framework\\http\\Request');
    }

    public function testThatGetByIdIsCalledWhenRequestIsGetAndProvidesId()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::GET));

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->partiallyMockedController->expects($this->once())
            ->method('getById')
            ->with(self::ANY_ID, $this->response);

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testGetListRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::GET));

        $this->mockRequest->expects($this->once())
            ->method('getId');

        $this->partiallyMockedController->expects($this->once())
            ->method('getList')
            ->with($this->response);

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testNeitherRouteCalledIfNotGetCall()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue('not-GET'));

        $this->mockRequest->expects($this->never())
            ->method('getId');

        $this->partiallyMockedController->expects($this->never())
            ->method('getList');

        $this->partiallyMockedController->expects($this->never())
            ->method('getById');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }
} 