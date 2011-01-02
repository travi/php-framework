<?php
/**
 * User: travi
 * Date: Jan 1, 2011
 * Time: 5:43:55 PM
 */
 
abstract class AbstractController
{
    public function doAction(&$request, &$response)
    {
        $action = $request->getAction();

        if(method_exists($this, $action))
        {
            $this->$action();
        }
        else
        {
            throw new NotFoundException($action . ' Action Not Found!');
        }
    }
}
