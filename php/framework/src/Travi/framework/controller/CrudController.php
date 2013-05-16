<?php

namespace Travi\framework\controller;

use Travi\framework\controller\AbstractController,
    Travi\framework\http\Request,
    Travi\framework\http\Response;

class CrudController extends AbstractController
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
                return $this->getList($response);
            } else {
                return $this->getById($id, $response);
            }
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
        $response->setTitle(static::ADD_HEADING);
        $response->setContent(
            array(
                'heading' => static::ADD_HEADING,
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
        $response->setTitle(static::EDIT_HEADING);
        $response->setContent(
            array(
                'heading' => static::EDIT_HEADING,
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
}
