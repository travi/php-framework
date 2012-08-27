<?php

use Travi\framework\content\navigation\Accordion;

class AccordionTest extends PHPUnit_Framework_TestCase
{
    /** @var Accordion */
    protected $accordion;

    protected function setUp()
    {
        $this->accordion = new Accordion;
    }

    public function testClientDependencies()
    {
        $this->assertSame(
            array(
                 'scripts'   => array('jqueryUi'),
                 'jsInits'   => array(
                                    "$('.accordion').accordion();"
                 ),
                 'styles'    => array()
            ),
            $this->accordion->getDependencies()
        );
        $this->assertSame('components/accordion.tpl', $this->accordion->getTemplate());
    }
}
