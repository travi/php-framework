<?php

abstract class AbstractController
{
    /**
     * @param $request Request
     * @param $response Response
     * @return void
     */
    public function doAction(&$request, &$response, $action = '', $extra)
    {
        if (empty($action)) {
            $action = $request->getAction();
        }

        if (method_exists($this, $action)) {
            $response->loadPageDependencies(get_class($this), $action);
            $this->$action($request, $response, $extra);
        } else {
            throw new NotFoundException($action . ' Action Not Found!');
        }
    }
}
