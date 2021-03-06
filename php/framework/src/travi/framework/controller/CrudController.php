<?php

namespace travi\framework\controller;

use travi\framework\components\Forms\Form;
use travi\framework\components\Forms\inputs\HiddenInput;
use travi\framework\components\Forms\inputs\TextInput;
use travi\framework\components\Forms\SubmitButton;
use travi\framework\controller\AbstractController,
    travi\framework\http\Request,
    travi\framework\http\Response;
use travi\framework\mappers\CrudMapper;
use travi\framework\model\CrudModel;
use travi\framework\view\objects\LinkView;

abstract class CrudController extends RestController
{
    /** @var  CrudMapper */
    protected $mapper;
    /** @var  CrudModel */
    protected $model;

    /**
     * @param $request Request
     * @param $response Response
     */
    public function index(&$request, &$response)
    {
        $requestMethod = $request->getRequestMethod();

        if (Request::GET === $requestMethod) {
            parent::index($request, $response);
        } else {
            $id = $request->getId();

            switch ($requestMethod) {
            case Request::POST:
                if (empty($id)) {
                    $this->addToList($request, $response);
                    break;
                } else {
                    $this->updateById($id, $response);
                    break;
                }
            case Request::DELETE:
                if (empty($id)) {
                    $response->setStatus(Response::NOT_ALLOWED);
                    break;
                } else {
                    $this->deleteById($id, $response);
                }
            }
        }
    }

    /**
     * @param $response Response
     */
    protected function getList(&$response)
    {
        $response->setTitle($this->getEntityType() . ' Administration');
        $response->setPageTemplate('../components/entityList.tpl');

        $response->setContent(
            array(
                'list' => $this->mapper->mapListToEntityList($this->model->getList())
            )
        );
    }

    /**
     * @param $request Request
     * @param $response Response
     */
    public function add($request, $response)
    {
        $response->setTitle($this->getAddHeading());
        $response->setContent(
            array(
                'form' => $this->mapper->mapToForm(null, 'Add')
            )
        );
    }

    /**
     * @param $request Request
     * @param $response Response
     */
    public function edit($request, $response)
    {
        $response->setTitle($this->getEditHeading());

        $form      = $this->mapper->mapToForm($this->model->getById($request->getId()), 'Update');
        $form->key = 'edit-resource';
        $response->setContent(
            array(
                'form' => $form
            )
        );
    }

    /**
     * @param $id
     * @param $response Response
     */
    protected function getById($id, &$response)
    {
        $response->setPageTemplate('../wrap/entityWrapper.tpl');
        $response->setContent(
            array(
                'entity' => $this->mapper->mapToEntityBlock($this->model->getById($id))
            )
        );
    }

    /**
     * @param $request Request
     * @param $response Response
     */
    protected function addToList(&$request, &$response)
    {
        /** @var $form Form */
        $form = $this->mapper->mapRequestToForm();

        if ($form->hasErrors()) {
            $response->setTitle($this->getAddHeading());
            $response->addToResponse('form', $form);
            $response->setStatus(Response::BAD_REQUEST);
        } else {
            $createdId = $this->model->add($this->mapper->mapFromForm($form));

            $response->addToResponse('createdId', $createdId);
            $response->setStatus(Response::CREATED);
            $response->setHeader(
                'Location: http://' . $request->getHost() . $this->getUrlPrefix() . $createdId
            );
            $response->showResults(
                'good',
                $this->getEntityType() . ' Added Successfully',
                $this->getUrlPrefix()
            );
        }
    }

    /**
     * @param $id
     * @param $response Response
     */
    protected function updateById($id, &$response)
    {
        $form = $this->mapper->mapRequestToForm();

        if ($form->hasErrors()) {
            $response->setTitle($this->getEditHeading());
            $response->setContent(
                array(
                    'form' => $form
                )
            );
            $response->setStatus(Response::BAD_REQUEST);
        } else {
            $this->model->updateById($id, $this->mapper->mapFromForm($form));
            $response->addToResponse('resource', $this->getUrlPrefix() . $id);
            $response->showResults(
                'good',
                $this->getEntityType() . ' Updated Successfully',
                $this->getUrlPrefix()
            );
        }
    }

    /**
     * @param Request $request
     * @param $response Response
     */
    public function remove(&$request, &$response)
    {
        $form      = new Form(
            array(
                'action' => $this->getUrlPrefix() . $request->getId()
            )
        );
        $form->key = 'remove-resource';
        $form->addFormElement(
            new HiddenInput(
                array(
                    'name' => '_method',
                    'value' => 'delete'
                )
            )
        );
        $cancelLink = new LinkView('Cancel', $this->getUrlPrefix());
        $cancelLink->addTag('cancel');
        $form->addAction($cancelLink);
        $form->addAction(
            new SubmitButton(
                array(
                    'label' => 'Remove'
                )
            )
        );

        $response->setPageTemplate('../components/form/removeConfirmation.tpl');
        $response->setContent(
            array(
                'form' => $form,
                'type' => $this->getEntityType()
            )
        );
    }


    /**
     * @param $id
     * @param $response Response
     */
    protected function deleteById($id, &$response)
    {
        //TODO: should be 'method not allowed'
        $response->setStatus(Response::NOT_IMPLEMENTED);
    }

    /**
     * @param $mapper CrudMapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    abstract protected function getEditHeading();
    abstract protected function getAddHeading();
    abstract protected function getUrlPrefix();
    abstract protected function getEntityType();
}
