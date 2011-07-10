<?php

abstract class AbstractController
{
    /**
     * @param $request Request
     * @param $response Response
     * @return void
     */
    public function doAction(&$request, &$response)
    {
        $action = $request->getAction();

        if (method_exists($this, $action)) {
            $this->$action($request, $response);
        } else {
            throw new NotFoundException($action . ' Action Not Found!');
        }
    }
}
