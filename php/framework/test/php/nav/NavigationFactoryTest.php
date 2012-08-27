<?php

use Travi\framework\content\navigation\NavigationFactory,
    Travi\framework\exception\NavigationTypeNotAnOptionException;

class NavigationFactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var NavigationFactory */
    private $factory;

    public function setUp()
    {
        $this->factory = new NavigationFactory();
    }

    public function testOptionsDefinedProperly()
    {
        $this->assertEquals('tabs', NavigationFactory::TABS);
        $this->assertEquals('accordion', NavigationFactory::ACCORDION);
        $this->assertEquals('menuBar', NavigationFactory::MENU_BAR);
    }

    public function testBuildAccordion()
    {
        $this->assertInstanceOf(
            'Travi\\framework\\content\\navigation\\Accordion',
            $this->factory->build(NavigationFactory::ACCORDION)
        );
    }

    public function testBuildTabs()
    {
        $this->assertInstanceOf(
            'Travi\\framework\\content\\navigation\\Tabs',
            $this->factory->build(NavigationFactory::TABS)
        );
    }

    public function testBuildMenuBar()
    {
        $this->assertInstanceOf(
            'Travi\\framework\\content\\navigation\\MenuBar',
            $this->factory->build(NavigationFactory::MENU_BAR)
        );
    }

    /**
     * @expectedException Travi\framework\exception\NavigationTypeNotAnOptionException
     * @expectedExceptionMessage someInvalidOption is not a valid navigation type
     */
    public function testBuildInvalidOption()
    {
        $this->factory->build('someInvalidOption');
    }
}
