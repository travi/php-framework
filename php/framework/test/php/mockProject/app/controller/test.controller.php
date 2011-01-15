<?php
require_once(dirname(__FILE__).'/../../../../../src/controller/abstract.controller.php');

class test extends AbstractController
{
    /** @var $response Response */
    /** @var $request Request */
    public function index(&$request, &$response)
    {
        $response->setTitle('Test');
    }
}
