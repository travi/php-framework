<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/controller/front/front.controller.php';
require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';

class FrontControllerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var FrontController
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new FrontController;

        $config = array('docRoot' => dirname(__FILE__) . '/../mockProject/doc_root/');

        $this->object->setConfig($config);
    }

    public function testProcessRequest()
    {
        $requestStub = $this->getMock('Request');
        $requestStub->expects($this->any())
            ->method('getController')
            ->will($this->returnValue('test'));
        $requestStub->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('index'));
        $requestStub->expects($this->once())
            ->method('isAdmin')
            ->will($this->returnValue(false));

        $responseStub = $this->getMock('Response');
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Test'));

        $this->object->setRequest($requestStub);
        $this->object->setResponse($responseStub);

        /** @var $response Response */
        $response = $this->object->processRequest();
    }

    public function test404()
    {
        $requestStub = $this->getMock('Request');
        $requestStub->expects($this->any())
            ->method('getController')
            ->will($this->returnValue('nonExistantPage'));

        $responseStub = $this->getMock('Response');
        //TODO: figure out a way to test the 404 header
        $responseStub->expects($this->once())
            ->method('setTitle')
            ->with($this->equalTo('Page Could Not Be Found'));
        $responseStub->expects($this->once())
            ->method('setPageTemplate')
            ->with($this->equalTo('../error/404.tpl'));

        $this->object->setRequest($requestStub);
        $this->object->setResponse($responseStub);     

        $response = $this->object->processRequest();
    }

    /**
     * @todo Implement test500().
     */
    public function test500()
    {
        $requestStub = $this->getMock('Request');
        //        $requestStub->expects($this->any())
        //                ->method('getController')
        //                ->will($this->returnValue('nonExistantPage'));

        $this->object->setRequest($requestStub);

        //        $response = $this->object->processRequest();
        
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
