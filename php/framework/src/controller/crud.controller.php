<?php

class CrudController extends AbstractController
{
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
        }
    }

    public function getList(&$response)
    {

    }

    public function getById($id, &$response)
    {

    }

    public function updateById($id, &$response)
    {
    }

    public function addToList(&$response)
    {
    }
}
