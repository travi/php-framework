<?php
require_once dirname(__FILE__).'/../../../../../src/controller/abstract.controller.php';

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
     * @return void
     */
    public function throwsError(&$request, &$response)
    {
        throw new Exception;
    }
}
