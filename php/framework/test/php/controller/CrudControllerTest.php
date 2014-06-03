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
    private $mapper;
    private $model;
    private $modelDataById = array();
    private $form;
    /** @var  ConcreteCrudController */
    private $crudController;
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
        $this->form = new Form();

        $this->crudController = new ConcreteCrudController();

        /** @var CrudMapper $mapper */
        $this->mapper = $this->getMockForAbstractClass('travi\\framework\\mappers\\CrudMapper');

        /** @var $model CrudModel */
        $this->model = $this->getMockForAbstractClass('travi\\framework\\model\\CrudModel');

        $this->crudController->setMapper($this->mapper);
        $this->crudController->setModel($this->model);

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

    public function testThatGetByIdReturnsEntityBlock()
    {
        $this->mockRequest->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::GET));
        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->model->expects($this->once())
            ->method('getById')
            ->with(self::ANY_ID)
            ->will($this->returnValue($this->modelDataById));

        $this->mapper->expects($this->once())
            ->method('mapToEntityBlock')
            ->with($this->modelDataById)
            ->will($this->returnValue($this->form));

        $this->crudController->index($this->mockRequest, new Response());
    }

    public function testThatEditEndpointReturnsEditForm()
    {
        $heading = 'some heading';

        $this->model->expects($this->once())
            ->method('getById')
            ->with(self::ANY_ID)
            ->will($this->returnValue($this->modelDataById));

        $this->mapper->expects($this->once())
            ->method('mapToForm')
            ->with($this->modelDataById, 'Update')
            ->will($this->returnValue($this->form));

        $this->crudController->setEditHeading($heading);

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
                    'form' => $this->form
                )
            );

        $this->crudController->edit($this->mockRequest, $responseMock);
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
    private $editHeading;
    private $addHeading;

    public function setAddHeading($string)
    {
        $this->addHeading = $string;
    }

    public function setEditHeading($heading)
    {
        $this->editHeading = $heading;
    }

    protected function getEditHeading()
    {
        return $this->editHeading;
    }

    protected function getAddHeading()
    {
        return $this->addHeading;
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