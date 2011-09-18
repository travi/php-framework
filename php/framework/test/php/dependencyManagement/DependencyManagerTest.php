<?php
require_once dirname(__FILE__) . '/../../../src/utilities/Environment.php';

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
    const SITE_THEME = 'site theme';
    private $environmentUtility;

    /** @var FileSystem */
    private $fileSystem;
    /** @var DependencyManager */
    private $dependencyManager;

    public function setUp()
    {
        $this->fileSystem = $this->getMock('FileSystem');
        $this->environmentUtility = $this->getMock('Environment');


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

    public function testPageStyleFollowsBaseFormSheet()
    {
        $this->dependencyManager->setPageStyle($this->pageStyle);
        $this->dependencyManager->resolveContentDependencies(array(new Form(array())));

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                '/resources/shared/css/travi.form.css',
                DependencyManager::THIS_PAGE_KEY => $this->pageStyle
            ),
            $dependencies['css']
        );
    }

    public function testSiteThemeFollowsBaseFormSheet()
    {
        $this->dependencyManager->setSiteTheme(self::SITE_THEME);
        $this->dependencyManager->resolveContentDependencies(array(new Form(array())));

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                '/resources/shared/css/travi.form.css',
                DependencyManager::SITE_THEME_KEY => self::SITE_THEME
            ),
            $dependencies['css']
        );
    }

    public function testPageStyleFollowsBaseSiteTheme()
    {
        $this->dependencyManager->setPageStyle($this->pageStyle);
        $this->dependencyManager->setSiteTheme(self::SITE_THEME);

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                DependencyManager::SITE_THEME_KEY => self::SITE_THEME,
                DependencyManager::THIS_PAGE_KEY => $this->pageStyle
            ),
            $dependencies['css']
        );
    }

    public function testReturnsMinifiedFormOfJsAndCss()
    {
        $script = "/js/some script";
        $sheet = "/css/some sheet";

        /** @var $environmentUtility Environment */
        $this->environmentUtility->expects($this->once())
            ->method('isLocal');

        $this->dependencyManager->setEnvironmentUtility($this->environmentUtility);
        $this->dependencyManager->addStyleSheet($sheet);
        $this->dependencyManager->addJavaScript($script);
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();

        $this->assertNotNull($dependencies);
        $this->assertSame(
            array(
                'css' => array(DependencyManager::MIN_DIR . $sheet),
                'js' => array(DependencyManager::MIN_DIR . $script)
            ),
            $dependencies
        );
    }

    public function testReturnsFullSourceFormOfJsAndCssWhenLocal()
    {
        $script = "/js/some script";
        $sheet = "/css/some sheet";

        /** @var $environmentUtility Environment */
        $this->environmentUtility->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(true));

        $this->dependencyManager->setEnvironmentUtility($this->environmentUtility);
        $this->dependencyManager->addStyleSheet($sheet);
        $this->dependencyManager->addJavaScript($script);
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();

        $this->assertNotNull($dependencies);
        $this->assertSame(
            array(
                'css' => array($sheet),
                'js' => array($script)
            ),
            $dependencies
        );
    }

    public function testReturnsFullSourceFormOfJsAndCssWhenDebug()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
