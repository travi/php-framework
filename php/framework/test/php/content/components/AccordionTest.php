<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/objects/content/navigation/accordion.class.php';

/**
 * Test class for Accordion.
 * Generated by PHPUnit on 2011-01-15 at 12:07:00.
 */
class AccordionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Accordion
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Accordion;
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
                                'jsInits'   => array("$('.accordion').accordion({animated: 'easeslide', header: 'dt'});"),
                                'styles'    => array()), $this->object->getDependencies());
        $this->assertSame('components/accordion.tpl', $this->object->getTemplate());
    }
}
?>