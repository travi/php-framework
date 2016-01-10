<?php

use travi\framework\controller\AbstractController,
    travi\framework\http\Request,
    travi\framework\http\Response;

class Test extends AbstractController
{
    /**
     * @param $request Request
     * @param $response Response
     * @return array
     */
    public function index(&$request, &$response)
    {
        $response->setTitle('Test');

        return array(
            'key1' => 'someContent'
        );
    }

    /**
     * @param $request Request
     * @param $response Response
     * @return array
     */
    public function noContentToReturn(&$request, &$response)
    {

    }

    /**
     * @param $request Request
     * @param $response Response
     * @throws Exception
     * @return void
     */
    public function throwsError(&$request, &$response)
    {
        throw new \Exception();
    }
}
