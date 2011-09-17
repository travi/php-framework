<?php
 
class DependencyManagerTest extends PHPUnit_Framework_TestCase
{
    private $anyController = 'testController';
    private $anyAction = 'testAction';

    private $jsDeps = array(
        'dep1',
        'dep2',
        'dep3'
    );
    private $siteWidgets = array(
        'siteWidget'
    );
    private $pageStyles = array(
        'page style sheet'
    );
    private $pageStyle = 'page.css';

    /** @var FileSystem */
    private $fileSystem;
    /** @var DependencyManager */
    private $dependencyManager;

    public function setUp()
    {
        $this->fileSystem = $this->getMock('FileSystem');

        $this->dependencyManager = new DependencyManager();
        $this->dependencyManager->setClientDependencyDefinitions(new ClientDependencies());
        $this->dependencyManager->setFileSystem($this->fileSystem);
    }

    public function testLoadPageDependenciesAddsFromList()
    {
        $this->dependencyManager->setPageDependenciesLists(
            array(
                 'site' => array(
                     'js' => $this->siteWidgets
                 ),
                 strtolower($this->anyController) => array(
                     $this->anyAction => array(
                         'js' => $this->jsDeps,
                         'css' => $this->pageStyles,
                         'pageStyle' => $this->pageStyle
                     )
                 )
            )
        );

        $this->dependencyManager->loadPageDependencies($this->anyController, $this->anyAction);

        $this->assertNotNull($this->dependencyManager->getStyleSheets());
        $this->assertNotNull($this->dependencyManager->getScripts());
        $this->assertSame(
            array_merge($this->siteWidgets, $this->jsDeps),
            $this->dependencyManager->getScripts()
        );
        $this->assertSame($this->pageStyle, $this->dependencyManager->getPageStyle());
        $this->assertSame(
            array_merge($this->pageStyles, array('thisPage' => $this->pageStyle)),
            $this->dependencyManager->getStyleSheets()
        );
    }

    public function testPageStyleNotSetIfEmpty()
    {
        $this->dependencyManager->setPageDependenciesLists(
            array(
                 'site' => array(
                     'js' => $this->siteWidgets
                 ),
                 strtolower($this->anyController) => array(
                     $this->anyAction => array(
                         'js' => $this->jsDeps,
                         'css' => $this->pageStyles
                     )
                 )
            )
        );

        $this->dependencyManager->loadPageDependencies($this->anyController, $this->anyAction);

        $this->assertSame(null, $this->dependencyManager->getPageStyle());
        $this->assertSame(
            $this->pageStyles,
            $this->dependencyManager->getStyleSheets()
        );
    }

    public function testAddPageStyleByConvention()
    {
        $pageStyle = 'path to page style';

        $this->fileSystem->expects($this->once())
            ->method('getPageStyleByConvention')
            ->will($this->returnValue($pageStyle));

        $this->dependencyManager->loadPageDependencies($this->anyController, $this->anyAction);

        $this->assertSame($pageStyle, $this->dependencyManager->getPageStyle());
    }
}
