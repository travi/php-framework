<?php

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
    }
}