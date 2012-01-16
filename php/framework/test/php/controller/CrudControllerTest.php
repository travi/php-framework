<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/controller/crud.controller.php';
require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';

class CrudControllerTest extends PHPUnit_Framework_TestCase
{
    const ANY_ID = 42;
    /** @var CrudController */
    private $partiallyMockedController;
    /** @var Response */
    private $response;
    /** @var Request */
    private $mockRequest;

    public function setUp()
    {
        $this->partiallyMockedController = $this->getMock(
            'CrudController',
            array(
                'getList',
                'getById',
                'addToList',
                'updateById',
                'deleteById'
            )
        );

        $this->response = new Response(array());
        $this->mockRequest = $this->getMock('Request');
    }

    public function testGetListRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::GET));

        $this->mockRequest->expects($this->once())
            ->method('getId');

        $this->partiallyMockedController->expects($this->once())
            ->method('getList');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testGetByIdRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::GET));

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->partiallyMockedController->expects($this->once())
            ->method('getById');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testAddTolistRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));

        $this->mockRequest->expects($this->once())
            ->method('getId');

        $this->partiallyMockedController->expects($this->once())
            ->method('addTolist');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testUpdateByIdRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->partiallyMockedController->expects($this->once())
            ->method('updateById');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testDeleteListIsNotAllowed()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::DELETE));
        $this->mockRequest->expects($this->once())
            ->method('getId');

        $responseMock = $this->getMock('Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_ALLOWED);

        $this->partiallyMockedController->index($this->mockRequest, $responseMock);
    }

    public function testDeleteByIdRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::DELETE));

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->partiallyMockedController->expects($this->once())
            ->method('deleteById');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testGetListDefaultNotImplemented()
    {
        $responseMock = $this->getMock('Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_IMPLEMENTED);

        $crudController = new CrudController();

        $crudController->getList($responseMock);
    }

    public function testGetByIdDefaultNotImplemented()
    {
        $responseMock = $this->getMock('Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_IMPLEMENTED);

        $crudController = new CrudController();

        $crudController->getById(self::ANY_ID, $responseMock);
    }

    public function testAddToListDefaultNotImplemented()
    {
        $responseMock = $this->getMock('Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_IMPLEMENTED);

        $crudController = new CrudController();

        $crudController->addToList($responseMock);
    }

    public function testUpdateByIdDefaultNotImplemented()
    {
        $responseMock = $this->getMock('Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_IMPLEMENTED);

        $crudController = new CrudController();

        $crudController->updateById(self::ANY_ID, $responseMock);
    }

    public function testDeleteByIdDefaultNotImplemented()
    {
        $responseMock = $this->getMock('Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_IMPLEMENTED);

        $crudController = new CrudController();

        $crudController->deleteById(self::ANY_ID, $responseMock);
    }
}