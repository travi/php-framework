<?php
use travi\framework\components\Forms\Form;
use travi\framework\http\Response,
    travi\framework\http\Request,
    travi\framework\controller\CrudController;
use travi\framework\mappers\CrudMapper;
use travi\framework\model\CrudModel;

class CrudControllerTest extends PHPUnit_Framework_TestCase
{
    const ANY_ID = 42;
    /** @var CrudController */
    public $abstractMock;
    /** @var CrudController */
    private $partiallyMockedController;
    /** @var Response */
    private $response;
    /** @var Request */
    private $mockRequest;

    public function setUp()
    {
        $this->abstractMock = $this->getMockForAbstractClass(
            'travi\\framework\\controller\\CrudController',
            array(),
            '',
            false,
            false,
            true,
            array()
        );
        $this->partiallyMockedController = $this->getMockForAbstractClass(
            'travi\\framework\\controller\\CrudController',
            array(),
            '',
            false,
            false,
            true,
            array(
                'getList',
                'getById',
                'addToList',
                'updateById',
                'deleteById'
            )
        );

        $this->response = new Response(array());
        $this->mockRequest = $this->getMock('travi\\framework\\http\\Request');
    }

    public function testGetListRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->any())
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
        $this->mockRequest->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::GET));

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->partiallyMockedController->expects($this->once())
            ->method('getById');

        $this->partiallyMockedController->index($this->mockRequest, $this->response);
    }

    public function testThatEditEndpointReturnsEditForm()
    {
        $crudController = new ConcreteCrudController();

        $valueFromModel = array();
        $form = new Form();
        $heading = 'some heading';

        /** @var $model CrudModel */
        $model = $this->getMockForAbstractClass('travi\\framework\\model\\CrudModel');
        $model->expects($this->once())
            ->method('getById')
            ->with(self::ANY_ID)
            ->will($this->returnValue($valueFromModel));

        /** @var CrudMapper $mapper */
        $mapper = $this->getMockForAbstractClass('travi\\framework\\mappers\\CrudMapper');
        $mapper->expects($this->once())
            ->method('mapToForm')
            ->with($valueFromModel)
            ->will($this->returnValue($form));

        $crudController->setModel($model);
        $crudController->setMapper($mapper);
        $crudController->setAddHeading($heading);

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        /** @var Response $responseMock */
        $responseMock = $this->getMock('travi\\framework\\http\\Response');
        $responseMock->expects($this->once())
            ->method('setTitle')
            ->with($heading);
        $responseMock->expects($this->once())
            ->method('setContent')
            ->with(
                array(
                    'form' => $form
                )
            );


        $crudController->edit($this->mockRequest, $responseMock);
    }

    public function testAddToListRoutesToProperMethod()
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

        $responseMock = $this->getMock('travi\\framework\\http\\Response');
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

    public function testDeleteByIdDefaultNotImplemented()
    {
        $responseMock = $this->getMock('travi\\framework\\http\\Response');
        $responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::NOT_IMPLEMENTED);

        $this->abstractMock->deleteById(self::ANY_ID, $responseMock);
    }
}

class ConcreteCrudController extends CrudController
{

    private $heading;

    public function setAddHeading($string)
    {
        $this->heading = $string;
    }

    protected function getEditHeading()
    {
        // TODO: Implement getEditHeading() method.
    }

    protected function getAddHeading()
    {
        return $this->heading;
    }

    protected function getUrlPrefix()
    {
        // TODO: Implement getUrlPrefix() method.
    }

    protected function getEntityType()
    {
        // TODO: Implement getEntityType() method.
    }
}