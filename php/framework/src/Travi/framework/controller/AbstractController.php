<?php

namespace Travi\framework\controller;

use Travi\framework\http\Response,
    Travi\framework\http\Request,
    Travi\framework\exception\NotFoundException;

abstract class AbstractController
{
    protected $model;

    /**
     * @param $request Request
     * @param $response Response
     * @param string $action
     * @param string $extra
     * @throws NotFoundException
     * @return array
     */
    public function doAction(&$request, &$response, $action = '', $extra = '')
    {
        if (empty($action)) {
            $action = $request->getAction();
        }

        $filters = $request->getFilters();
        if (method_exists($this, $action)) {
            return $this->$action($request, $response, $filters, $extra);
        } else {
            throw new NotFoundException($action . ' Action Not Found!');
        }
    }

    public function setModel($model)
    {
        $this->model = $model;
    }
}
