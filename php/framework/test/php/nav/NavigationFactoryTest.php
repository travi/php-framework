<?php
require_once dirname(__FILE__).'/../../../objects/content/navigation/navigation.factory.php';

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
        $this->assertType('Accordion', $this->factory->build(NavigationFactory::ACCORDION));
    }

    public function testBuildTabs()
    {
        $this->assertType('Tabs', $this->factory->build(NavigationFactory::TABS));
    }

    public function testBuildMenuBar()
    {
        $this->assertType('MenuBar', $this->factory->build(NavigationFactory::MENU_BAR));
    }

    /**
     * @expectedException NavigationTypeNotAnOption
     * @expectedExceptionMessage someInvalidOption is not a valid navigation type
     */
    public function testBuildInvalidOption()
    {
        $this->factory->build('someInvalidOption');
    }
}
