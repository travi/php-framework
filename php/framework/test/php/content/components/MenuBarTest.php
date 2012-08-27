<?php

use Travi\framework\content\navigation\MenuBar;

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
                'scripts'   => array('jqueryUi', '/resources/shared/js/plugins/jquery.menubar.js'),
                'jsInits'   => array("$('ul.menuBar').menuBar();"),
                'styles'    => array('/resources/shared/css/ui/menuBar.css')
            ),
            $this->menu->getDependencies()
        );
        $this->assertSame('components/menuBar.tpl', $this->menu->getTemplate());
    }
}
