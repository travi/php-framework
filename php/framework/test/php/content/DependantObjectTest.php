<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../objects/dependantObject.class.php';

class DependantObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DependantObject
     */
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('DependantObject');
    }

    public function testStyleSheet()
    {
        $sheet = 'sheet';
        $this->object->addStyleSheet($sheet);
        $this->assertSame(array($sheet), $this->object->getStyles());
    }

    public function testJavaScript()
    {
        $js = 'js';
        $this->object->addJavaScript($js);
        $this->assertSame(array($js), $this->object->getJavaScripts());
    }

    public function testAddJsInit()
    {
        $jsInit = 'jsInit';
        $this->object->addJsInit($jsInit);
        $this->assertSame(array($jsInit), $this->object->getJsInits());
    }

    public function testTemplate()
    {
        $template = 'template';
        $this->object->setTemplate($template);
        $this->assertSame($template, $this->object->getTemplate());
    }

    public function testGetDependencies()
    {
        $sheet = 'sheet';
        $this->object->addStyleSheet($sheet);
        $js = 'js';
        $this->object->addJavaScript($js);
        $jsInit = 'jsInit';
        $this->object->addJsInit($jsInit);
        $this->assertSame(
            array(
                 'scripts' => array($js),
                 'jsInits' => array($jsInit),
                 'styles'  => array($sheet)
            ),
            $this->object->getDependencies()
        );
    }
}
