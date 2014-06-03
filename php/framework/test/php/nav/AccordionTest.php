<?php

use travi\framework\navigation\Accordion;

class AccordionTest extends PHPUnit_Framework_TestCase
{
    /** @var Accordion */
    protected $accordion;

    protected function setUp()
    {
        $this->accordion = new Accordion();
    }

    public function testClientDependencies()
    {
        $this->assertEquals(
            array(
                'scripts' => array('jqueryUi'),
                'jsInits' => array("$('.accordion').accordion();"),
                'styles' => array()
            ),
            $this->accordion->getDependencies()
        );
        $this->assertEquals('components/accordion.tpl', $this->accordion->getTemplate());
    }
}
