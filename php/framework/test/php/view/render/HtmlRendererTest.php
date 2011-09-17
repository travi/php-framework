<?php
require_once dirname(__FILE__) . '/../../../../../thirdparty/smarty/Smarty.class.php';

class HtmlRendererTest extends PHPUnit_Framework_TestCase
{
    const SOME_LAYOUT_TEMPLATE = 'some layout template';

    public function testAttributesAddedToSmartyBeforeRendering()
    {
        $data = array();
        $deps = array('some dependencies');

        $dependencyManager = $this->getMock('DependencyManager');
        $dependencyManager->expects($this->once())
            ->method('resolveContentDependencies')
            ->with($data);
        $dependencyManager->expects($this->once())
            ->method('addCacheBusters');
        $dependencyManager->expects($this->once())
            ->method('getDependencies')
            ->will($this->returnValue($deps));

        $page = $this->getMock('Response');

        $smarty = $this->getMock('Smarty');
        $smarty->expects($this->once())
            ->method('clearAllAssign');
        $smarty->expects($this->at(1))
            ->method('assign')
            ->with('dependencies', $deps);
        $smarty->expects($this->at(2))
            ->method('assign')
            ->with('page', $page);
        $smarty->expects($this->once())
            ->method('display')
            ->with(self::SOME_LAYOUT_TEMPLATE);

        $htmlRenderer = new HtmlRenderer();
        $htmlRenderer->setLayoutTemplate(self::SOME_LAYOUT_TEMPLATE);
        $htmlRenderer->setDependencyManager($dependencyManager);
        $htmlRenderer->setSmarty($smarty);
        $htmlRenderer->format($data, $page);
    }
}
