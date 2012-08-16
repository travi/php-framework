<?php

class HtmlRendererTest extends PHPUnit_Framework_TestCase
{
    const SOME_LAYOUT_TEMPLATE = 'some layout template';
    const SOME_PATH = 'somePath';
    const SOME_CONTROLLER = 'someController';
    const SOME_ACTION = 'someAction';

    /** @var HtmlRenderer */
    private $htmlRenderer;
    /** @var DependencyManager */
    private $dependencyManager;
    /** @var Smarty */
    private $smarty;
    /** @var Request */
    private $request;
    /** @var Response */
    private  $page;

    public function setUp()
    {
        $this->htmlRenderer = new HtmlRenderer();

        $this->dependencyManager = $this->getMock('DependencyManager');
        $this->smarty = $this->getMockBuilder('Smarty')
            ->disableOriginalConstructor()
            ->getMock();
        $this->request = $this->getMock('Request');
        $this->page = $this->getMock('Response');

        $this->htmlRenderer->setDependencyManager($this->dependencyManager);
        $this->htmlRenderer->setSmarty($this->smarty);
        $this->htmlRenderer->setRequest($this->request);
    }

    public function testAttributesAddedToSmartyBeforeRendering()
    {
        $data = array();
        $deps = array('some dependencies');

        $this->dependencyManager->expects($this->once())
            ->method('resolveContentDependencies')
            ->with($data);
        $this->dependencyManager->expects($this->once())
            ->method('addCacheBusters');
        $this->dependencyManager->expects($this->once())
            ->method('getDependenciesInProperForm')
            ->will($this->returnValue($deps));

        $this->page->expects($this->once())
            ->method('getPageTemplate')
            ->will($this->returnValue('something'));
        $this->page->expects($this->never())
            ->method('setPageTemplate');

        $this->smarty->expects($this->once())
            ->method('clearAllAssign');
        $this->smarty->expects($this->at(1))
            ->method('assign')
            ->with('dependencies', $deps);
        $this->smarty->expects($this->at(2))
            ->method('assign')
            ->with('page', $this->page);
        $this->smarty->expects($this->once())
            ->method('display')
            ->with(self::SOME_LAYOUT_TEMPLATE);

        $this->request->expects($this->once())
            ->method('getEnhancementVersion');

        $this->htmlRenderer->setLayoutTemplate(self::SOME_LAYOUT_TEMPLATE);
        $this->htmlRenderer->format($data, $this->page);
    }

    public function testPageLayoutSetByConventionIfNotAlreadySet()
    {
        $pathToTemplate = self::SOME_CONTROLLER . '/' . self::SOME_ACTION . '.tpl';

        $this->request->expects($this->once())
            ->method('getController')
            ->will($this->returnValue(self::SOME_CONTROLLER));
        $this->request->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue(self::SOME_ACTION));

        $fileSystem = $this->getMock('FileSystem');
        $fileSystem->expects($this->once())
            ->method('pageTemplateExists')
            ->with($pathToTemplate)
            ->will($this->returnValue(true));

        $this->page->expects($this->once())
            ->method('getPageTemplate')
            ->will($this->returnValue(''));
        $this->page->expects($this->once())
            ->method('setPageTemplate')
            ->with($pathToTemplate);

        $this->htmlRenderer->setFileSystem($fileSystem);

        $this->htmlRenderer->format(array(), $this->page);
    }

    /**
     * @expectedException MissingPageTemplateException
     */
    public function testExceptionThrownIfCannotSetPageTemplate()
    {
        $pathToTemplate = self::SOME_CONTROLLER . '/' . self::SOME_ACTION . '.tpl';

        $this->request->expects($this->once())
            ->method('getController')
            ->will($this->returnValue(self::SOME_CONTROLLER));
        $this->request->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue(self::SOME_ACTION));

        $fileSystem = $this->getMock('FileSystem');
        $fileSystem->expects($this->once())
            ->method('pageTemplateExists')
            ->with($pathToTemplate)
            ->will($this->returnValue(false));

        $this->page->expects($this->once())
            ->method('getPageTemplate')
            ->will($this->returnValue(''));
        $this->page->expects($this->never())
            ->method('setPageTemplate');

        $this->htmlRenderer->setFileSystem($fileSystem);

        $this->htmlRenderer->format(array(), $this->page);
    }
}
