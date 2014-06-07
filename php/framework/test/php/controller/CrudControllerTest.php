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
    /** @var  Form */
    private $form;
    const ANY_URL_PREFIX = 'some prefix';
    const ANY_TYPE = 'some type';
    const ANY_HOST = 'some host';
    const ANY_HEADING = 'some heading';
    /** @var Response */
    private $responseMock;
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
        $this->form = $this->getMock('travi\\framework\\components\\Forms\\Form');

        $this->crudController = new ConcreteCrudController();

        /** @var CrudMapper $mapper */
        $this->mapper = $this->getMockForAbstractClass('travi\\framework\\mappers\\CrudMapper');

        /** @var $model CrudModel */
        $this->model = $this->getMockForAbstractClass('travi\\framework\\model\\CrudModel');

        $this->crudController->setMapper($this->mapper);
        $this->crudController->setModel($this->model);
        $this->crudController->setAddHeading(self::ANY_HEADING);
        $this->crudController->setEditHeading(self::ANY_HEADING);
        $this->crudController->setUrlPrefix(self::ANY_URL_PREFIX);
        $this->crudController->setEntityType(self::ANY_TYPE);

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

        $this->responseMock = $this->getMock('travi\\framework\\http\\Response');
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

    public function testThatAddPersistsDataAndReturnsSuccessInformation()
    {
        $this->mockRequest->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));
        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(null));

        $this->form->expects($this->once())
            ->method('hasErrors')
            ->will($this->returnValue(false));

        $this->mapper->expects($this->once())
            ->method('mapRequestToForm')
            ->will($this->returnValue($this->form));
        $object = array();
        $this->mapper->expects($this->once())
            ->method('mapFromForm')
            ->with($this->form)
            ->will($this->returnValue($object));

        $this->model->expects($this->once())
            ->method('add')
            ->with($object)
            ->will($this->returnValue(self::ANY_ID));

        $this->mockRequest->expects($this->once())
            ->method('getHost')
            ->will($this->returnValue(self::ANY_HOST));

        $this->responseMock->expects($this->once())
            ->method('addToResponse')
            ->with('createdId', self::ANY_ID);
        $this->responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::CREATED);
        $this->responseMock->expects($this->once())
            ->method('setHeader')
            ->with('Location: http://' . self::ANY_HOST . self::ANY_URL_PREFIX . self::ANY_ID);
        $this->responseMock->expects($this->once())
            ->method('showResults')
            ->with('good', self::ANY_TYPE . ' Added Successfully', self::ANY_URL_PREFIX);

        $this->crudController->index($this->mockRequest, $this->responseMock);
    }

    public function testThatAddReturnsFormAfterValidationErrorToTryAgain()
    {
        $this->mockRequest->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));
        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(null));

        $this->form->expects($this->once())
            ->method('hasErrors')
            ->will($this->returnValue(true));

        $this->mapper->expects($this->once())
            ->method('mapRequestToForm')
            ->will($this->returnValue($this->form));

        $this->responseMock->expects($this->once())
            ->method('setTitle')
            ->with(self::ANY_HEADING);
        $this->responseMock->expects($this->once())
            ->method('addToResponse')
            ->with('form', $this->form);
        $this->responseMock->expects($this->once())
            ->method('setStatus')
            ->with(Response::BAD_REQUEST);

        $this->crudController->index($this->mockRequest, $this->responseMock);
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

        $this->responseMock->expects($this->once())
            ->method('setPageTemplate')
            ->with('../wrap/entityWrapper.tpl');
        $this->responseMock->expects($this->once())
            ->method('setContent')
            ->with(
                array(
                    'entity' => $this->form
                )
            );

        $this->crudController->index($this->mockRequest, $this->responseMock);
    }

    public function testThatEditEndpointReturnsEditForm()
    {
        $this->model->expects($this->once())
            ->method('getById')
            ->with(self::ANY_ID)
            ->will($this->returnValue($this->modelDataById));

        $this->mapper->expects($this->once())
            ->method('mapToForm')
            ->with($this->modelDataById, 'Update')
            ->will($this->returnValue($this->form));

        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->responseMock->expects($this->once())
            ->method('setTitle')
            ->with(self::ANY_HEADING);
        $this->responseMock->expects($this->once())
            ->method('setContent')
            ->with(
                array(
                    'form' => $this->form
                )
            );

        $this->crudController->edit($this->mockRequest, $this->responseMock);
    }

    public function testThatEditPersistsUpdateAndReturnsSuccessInformation()
    {
        $this->mockRequest->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));
        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->mapper->expects($this->once())
            ->method('mapRequestToForm')
            ->will($this->returnValue($this->form));
        $object = array();
        $this->mapper->expects($this->once())
            ->method('mapFromForm')
            ->with($this->form)
            ->will($this->returnValue($object));

        $this->form->expects($this->once())
            ->method('hasErrors')
            ->will($this->returnValue(false));

        $this->model->expects($this->once())
            ->method('updateById')
            ->with(self::ANY_ID, $object);

        $this->responseMock->expects($this->once())
            ->method('showResults')
            ->with('good', self::ANY_TYPE . ' Updated Successfully', self::ANY_URL_PREFIX);

        $this->crudController->index($this->mockRequest, $this->responseMock);
    }

    public function testThatEditReturnsFormAfterValidationErrorToTryAgain()
    {
        $this->mockRequest->expects($this->any())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));
        $this->mockRequest->expects($this->once())
            ->method('getId')
            ->will($this->returnValue(self::ANY_ID));

        $this->mapper->expects($this->once())
            ->method('mapRequestToForm')
            ->will($this->returnValue($this->form));

        $this->form->expects($this->once())
            ->method('hasErrors')
            ->will($this->returnValue(true));

        $this->responseMock->expects($this->once())
            ->method('setTitle')
            ->with(self::ANY_HEADING);
        $this->responseMock->expects($this->once())
            ->method('setContent')
            ->with(array('form' => $this->form));

        $this->crudController->index($this->mockRequest, $this->responseMock);
    }

    public function testAddToListRoutesToProperMethod()
    {
        $this->mockRequest->expects($this->once())
            ->method('getRequestMethod')
            ->will($this->returnValue(Request::POST));

        $this->mockRequest->expects($this->once())
            ->method('getId');

        $this->partiallyMockedController->expects($this->once())
            ->method('addToList');

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
    private $prefix;
    private $type;

    public function setUrlPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function setEntityType($type)
    {
        $this->type = $type;
    }

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
        return $this->prefix;
    }

    protected function getEntityType()
    {
        return $this->type;
    }
}