<?php

use travi\framework\navigation\MenuBar;

class MenuBarTest extends PHPUnit_Framework_TestCase
{
    /** @var MenuBar */
    protected $menu;

    protected function setUp()
    {
        $this->menu = new MenuBar();
    }

    public function testClientDependencies()
    {
        $this->assertSame(
            array(
                'scripts'   => array('jqueryUi', '/resources/thirdparty/travi-menubar/js/jquery.menubar.js'),
                'jsInits'   => array("$('ul.menuBar').menuBar();"),
                'styles'    => array('/resources/thirdparty/travi-menubar/css/ui/menuBar.css')
            ),
            $this->menu->getDependencies()
        );
        $this->assertSame('components/menuBar.tpl', $this->menu->getTemplate());
    }
}
