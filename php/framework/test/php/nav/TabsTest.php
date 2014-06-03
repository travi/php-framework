<?php

use travi\framework\navigation\Tabs;

class TabsTest extends PHPUnit_Framework_TestCase
{
    /** @var Tabs */
    protected $object;

    protected function setUp()
    {
        $this->object = new Tabs;
    }

    public function testClientDependencies()
    {
        $this->assertSame(
            array(
                 'scripts'   => array('jqueryUi'),
                 'jsInits'   => array(
            "$('.ui-tabs').tabs({
                selected: 0, fx: {opacity: 'toggle', height: 'toggle'}
            });"
                ),
                'styles'    => array()
            ),
            $this->object->getDependencies()
        );
        $this->assertSame('components/tabs.tpl', $this->object->getTemplate());
    }
}