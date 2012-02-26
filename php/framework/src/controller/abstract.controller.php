<?php

abstract class AbstractController
{
    protected $model;

    /**
     * @param $request Request
     * @param $response Response
     * @param string $action
     * @param string $extra
     * @return array
     */
    public function doAction(&$request, &$response, $action = '', $extra = '')
    {
        if (empty($action)) {
            $action = $request->getAction();
        }

        if (method_exists($this, $action)) {
            return $this->$action($request, $response, $extra);
        } else {
            throw new NotFoundException($action . ' Action Not Found!');
        }
    }

    public function setModel($model)
    {
        $this->model = $model;
    }
}
