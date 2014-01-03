<?php

namespace travi\framework\controller;


use travi\framework\http\Request;
use travi\framework\http\Response;

abstract class RestController extends AbstractController
{
    /**
     * @param $request Request
     * @param $response Response
     */
    public function index(&$request, &$response)
    {
        $requestMethod = $request->getRequestMethod();

        if (Request::GET === $requestMethod) {
            $id = $request->getId();

            if (empty($id)) {
                $this->getList($response);
            } else {
                $this->getById($id, $response);
            }
        }
    }

    /**
     * @param $id
     * @param $response Response
     */
    abstract public function getById($id, &$response);


    /**
     * @param $response Response
     */
    abstract public function getList(&$response);
}