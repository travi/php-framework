<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../../objects/content/navigation/menuBar.class.php';

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
