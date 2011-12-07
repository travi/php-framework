<?php
require_once dirname(__FILE__) . '/../../../src/http/Request.class.php';


class ClientDependenciesTest extends PHPUnit_Framework_TestCase
{
    private $dependencyDefinition;
    const SOME_COMPONENT = 'someComponent';
    const PATH_TO_SOME_COMPONENT = 'somePath';

    /** @var ClientDependencies */
    public $dependencies;

    public function setUp()
    {
        $this->dependencyDefinition = array(
            self::SOME_COMPONENT     => array(
                'local' => self::PATH_TO_SOME_COMPONENT
            )
        );

        $this->dependencies = new ClientDependencies();
        $this->dependencies->setRequest(new Request());
        $this->dependencies->setUiDeps($this->dependencyDefinition);
    }

    public function testFileUriResolvedForComponent()
    {
        $this->assertEquals(
            self::PATH_TO_SOME_COMPONENT,
            $this->dependencies->resolveFileURI(self::SOME_COMPONENT)
        );
    }

    public function testDependenciesReturnedForComponent()
    {
        $this->assertEquals(
            array(
                'local'             => self::PATH_TO_SOME_COMPONENT,
                'cdn'               => '',
                'jsDependencies'    => array(),
                'cssDependencies'   => ''
            ),
            $this->dependencies->getDependenciesFor(self::SOME_COMPONENT)
        );
    }

    /**
     * @expectedException           Exception
     */
    public function testComponentWithoutLocalPathThrowsException()
    {
        $this->dependencies->setUiDeps(
            array(
                'invalidComponent'  => array()
            )
        );

        $this->dependencies->resolveFileURI('invalidComponent');
    }

    public function testDesktopSpecificDependencyMapping()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->once())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::DESKTOP_ENHANCEMENT));
        $this->dependencies->setRequest($mockRequest);

        $this->dependencies->setUiDeps(
            array(
                self::SOME_COMPONENT    => array(
                    'desktop'   =>  array(
                        'local' =>  self::PATH_TO_SOME_COMPONENT,
                        'jsDependencies'    => array(
                            'jquery'
                        )
                    )
                )
            )
        );

        $this->assertEquals(
            self::PATH_TO_SOME_COMPONENT,
            $this->dependencies->resolveFileURI(self::SOME_COMPONENT)
        );
    }
}