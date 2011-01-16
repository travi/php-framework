<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/objects/content/navigation/tabs.class.php';

/**
 * Test class for Tabs.
 * Generated by PHPUnit on 2011-01-15 at 12:13:26.
 */
class TabsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Tabs
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Tabs;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testClientDependencies()
    {
        $this->assertSame(array('scripts'   => array('jqueryUi'),
                                'jsInits'   => array("$('.ui-tabs').tabs({selected: 0, fx: {opacity: 'toggle', height: 'toggle'}});"),
                                'styles'    => array()), $this->object->getDependencies());
        $this->assertSame('components/tabs.tpl', $this->object->getTemplate());
    }
}
?>