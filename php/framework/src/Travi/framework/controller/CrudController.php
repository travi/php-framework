<?php

namespace Travi\framework\controller;

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
     * @param $response Response
     */
    public function getList(&$response)
    {
        $response->setStatus(Response::NOT_IMPLEMENTED);
    }

    /**
     * @param $id
     * @param $response Response
     */
    public function updateById($id, &$response)
    {
        $response->setStatus(Response::NOT_IMPLEMENTED);
    }

    /**
     * @param $response Response
     */
    public function addToList(&$response)
    {
        $response->setStatus(Response::NOT_IMPLEMENTED);
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
}
