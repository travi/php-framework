<?php

namespace Travi\framework\controller;

use Travi\framework\components\Forms\Form;
use Travi\framework\controller\AbstractController,
    Travi\framework\http\Request,
    Travi\framework\http\Response;

abstract class CrudController extends AbstractController
{
    /** @var  \CrudMapper */
    protected $mapper;

    /**
     * @param $request Request
     * @param $response Response
     */
    public function index(&$request, &$response)
    {
        $requestMethod = $request->getRequestMethod();
        $id = $request->getId();

        switch ($requestMethod) {
        case Request::GET:
            if (empty($id)) {
                $this->getList($response);
            } else {
                $this->getById($id, $response);
            }
            break;
        case Request::POST:
            if (empty($id)) {
                return $this->addToList($response);
            } else {
                return $this->updateById($id, $response);
            }
        case Request::DELETE:
            if (empty($id)) {
                $response->setStatus(Response::NOT_ALLOWED);
                break;
            } else {
                return $this->deleteById($id, $response);
            }
        }
    }

    /**
     * @param $response Response
     */
    public function getList(&$response)
    {
        $response->setTitle($this->getEntityType() . ' Administration');

        $response->setContent(
            array(
                'list' => $this->mapper->mapListToEntityList($this->model->getList())
            )
        );
    }

    /**
     * @param $id
     * @param $response Response
     */
    public function getById($id, &$response)
    {
        $response->setTitle($this->getEditHeading());
        $response->setContent(
            array(
                'heading' => $this->getEditHeading(),
                'form' => $this->mapper->mapToForm($this->model->getById($id), 'Update')
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
                'heading' => $this->getAddHeading(),
                'form' => $this->mapper->mapToForm(null, 'Add')
            )
        );
    }

    /**
     * @param $response Response
     */
    public function addToList(&$response)
    {
        /** @var $form Form */
        $form = $this->mapper->mapRequestToForm();

        if ($form->hasErrors()) {
            $response->setTitle($this->getAddHeading());
            $response->addToResponse('form', $form);
            $response->setContent(
                array(
                    'heading' => $this->getAddHeading(),
                    'form' => $form
                )
            );
            $response->setStatus(400);
        } else {
            $response->addToResponse('createdId', $this->model->add($this->mapper->mapFromForm($form)));
            $response->setStatus(201);
            $response->redirect(
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
    public function updateById($id, &$response)
    {
        $form = $this->mapper->mapRequestToForm();

        if ($form->hasErrors()) {
            $response->setTitle($this->getEditHeading());
            $response->setContent(
                array(
                    'heading' => $this->getEditHeading(),
                    'form' => $form
                )
            );
            $response->setStatus(400);
        } else {
            $this->model->updateById($id, $this->mapper->mapFromForm($form));
            $response->redirect(
                'good',
                $this->getEntityType() . ' Updated Successfully',
                $this->getUrlPrefix()
            );
        }
    }

    /**
     * @param $id
     * @param $response Response
     */
    public function deleteById($id, &$response)
    {
        $response->setStatus(Response::NOT_IMPLEMENTED);
    }

    /**
     * @param $mapper \CrudMapper
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
