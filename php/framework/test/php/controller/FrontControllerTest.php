<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/controller/front/front.controller.php';
require_once dirname(__FILE__).'/../../../src/http/Request.class.php';
require_once dirname(__FILE__).'/../../../src/http/Response.class.php';

//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Map.php';
//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Container/Maps.php';
//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Container.php';
//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Make/Abstract.php';
//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Make/Constructor.php';
//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Make.php';

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/'
);

//function __autoload($class_name)
//{
//    include_once str_replace('_', '/', $class_name) . '.php';
//}

//require_once dirname(__FILE__).'/../../../../thirdparty/PHP-Dependency/library/Pd/Container.php';
//$container = Pd_Container::get();

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
        //        $requestStub = $this->getMock('Request');
        //        $requestStub->expects($this->any())
        //            ->method('getController')
        //            ->will($this->returnValue('test'));
        //        $requestStub->expects($this->any())
        //            ->method('getAction')
        //            ->will($this->returnValue('index'));
        //        $requestStub->expects($this->any())
        //            ->method('isAdmin')
        //            ->will($this->returnValue(false));
        //
        //        $responseStub = $this->getMock('Response');
        //        $responseStub->expects($this->once())
        //            ->method('setTitle')
        //            ->with($this->equalTo('Test'));
        //
        //        $this->object->setRequest($requestStub);
        //        $this->object->setResponse($responseStub);
        //
        //        $this->object->processRequest();


        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function test404()
    {
        //        $requestStub = $this->getMock('Request');
        //        $requestStub->expects($this->exactly(2))
        //            ->method('getController')
        //            ->will($this->returnValue('nonExistantPage'));
        //
        //        $responseStub = $this->getMock('Response');
        //        //TODO: figure out a way to test the 404 header
        //        $responseStub->expects($this->once())
        //            ->method('setTitle')
        //            ->with($this->equalTo('Page Could Not Be Found'));
        //        $responseStub->expects($this->once())
        //            ->method('setPageTemplate')
        //            ->with($this->equalTo('../error/404.tpl'));
        //
        //        $this->object->setRequest($requestStub);
        //        $this->object->setResponse($responseStub);
        ////
        //        $this->object->processRequest();

        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
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
