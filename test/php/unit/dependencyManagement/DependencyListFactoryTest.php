<?php

use travi\framework\dependencyManagement\DependencyListFactory;

class DependencyListFactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var  DependencyListFactory */
    private $dependencyListFactory;

    public function setUp()
    {
        $this->dependencyListFactory = new DependencyListFactory();
    }

    /**
     * @expectedException travi\framework\exception\UnknownDependencyListException
     */
    public function testThatExceptionIsThrownWhenUnknownListTypeIsRequested()
    {
        $this->dependencyListFactory->createList('unknown');
    }

    public function testThatJavascriptListCreatedWhenJsRequested()
    {
        $list = $this->dependencyListFactory->createList('js');

        $this->assertInstanceOf('travi\\framework\\dependencyManagement\\JavascriptList', $list);
    }
}