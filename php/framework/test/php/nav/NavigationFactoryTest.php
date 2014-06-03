<?php

use travi\framework\navigation\NavigationFactory;

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
            'travi\\framework\\navigation\\Accordion',
            $this->factory->build(NavigationFactory::ACCORDION)
        );
    }

    public function testBuildTabs()
    {
        $this->assertInstanceOf(
            'travi\\framework\\navigation\\Tabs',
            $this->factory->build(NavigationFactory::TABS)
        );
    }

    public function testBuildMenuBar()
    {
        $this->assertInstanceOf(
            'travi\\framework\\navigation\\MenuBar',
            $this->factory->build(NavigationFactory::MENU_BAR)
        );
    }

    /**
     * @expectedException travi\framework\exception\NavigationTypeNotAnOptionException
     * @expectedExceptionMessage someInvalidOption is not a valid navigation type
     */
    public function testBuildInvalidOption()
    {
        $this->factory->build('someInvalidOption');
    }
}
