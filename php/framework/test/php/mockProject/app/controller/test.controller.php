<?php
require_once dirname(__FILE__).'/../../../../../src/controller/abstract.controller.php';

class test extends AbstractController
{
    /**
     * @param $request Request
     * @param $response Response
     * @return void
     */
    public function index(&$request, &$response)
    {
        $response->setTitle('Test');
    }
}
