<?php
require_once dirname(__FILE__) . '/../../../src/utilities/Environment.php';
require_once dirname(__FILE__) . '/../../../src/dependencyManagement/DependencyManager.class.php';
require_once dirname(__FILE__) . '/../../../src/http/Request.class.php';

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
        'pageSheet1.css'
    );
    private $pageStyle = 'page.css';
    private $pageStyleMobile = 'page_m.css';
    const SITE_THEME = 'siteTheme.css';
    const SITE_THEME_MOBILE = 'siteTheme_m.css';
    const SITE_THEME_DESKTOP = 'siteTheme_d.css';
    private $environmentUtility;
    private $request;

    private $commonInit = 'some common initialization';
    private $mobileInit = 'some device specific initialization';

    private $dependencyDefinition;


    /** @var FileSystem */
    private $fileSystem;
    /** @var DependencyManager */
    private $dependencyManager;

    public function setUp()
    {
        $this->fileSystem = $this->getMock('FileSystem');
        $this->environmentUtility = $this->getMock('Environment');
        $this->request = $this->getMock('Request');

        $this->dependencyManager = new DependencyManager();
        $this->dependencyManager->setClientDependencyDefinitions(new ClientDependencies());
        $this->dependencyManager->setFileSystem($this->fileSystem);
        $this->dependencyManager->setRequest($this->request);

        $this->request->expects($this->any())
            ->method('getController')
            ->will($this->returnValue($this->anyController));
        $this->request->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue($this->anyAction));

        $anyPageDependencies = array(
            $this->anyAction => array(
                'mobile' => array(
                    'jsInits' => array(
                        $this->mobileInit
                    )
                ),
                'js' => $this->jsDeps,
                'jsInits' => array(
                    $this->commonInit
                ),
                'css' => $this->pageStyles,
                'pageStyle' => $this->pageStyle
            )
        );

        $this->dependencyDefinition = array(
             'site' => array(
                 'js' => $this->siteWidgets
             ),
             strtolower($this->anyController) => $anyPageDependencies,
             'admin' => array(
                 strtoLower($this->anyController) => $anyPageDependencies
             )
        );
    }

    public function testLoadPageDependenciesAddsFromList()
    {
        $this->dependencyManager->setPageDependenciesLists($this->dependencyDefinition);

        $this->dependencyManager->loadPageDependencies();

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

        $this->dependencyManager->loadPageDependencies();

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

        $this->dependencyManager->loadPageDependencies();

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

    public function testPageStyleFollowsSiteTheme()
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

    public function testJsInitSpecificToEnhancementVersionWhenIfDefinedInConfig()
    {
        $this->dependencyManager->setPageDependenciesLists($this->dependencyDefinition);

        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::MOBILE_ENHANCEMENT));

        $this->dependencyManager->loadPageDependencies();

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                $this->commonInit,
                $this->mobileInit
            ),
            $dependencies['jsInit']
        );
    }

    public function testMobileCssLoadedInAdditionToBaseWhenMobileEnhanced()
    {
        $this->dependencyManager->setPageDependenciesLists($this->dependencyDefinition);

        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::MOBILE_ENHANCEMENT));

        $this->fileSystem->expects($this->any())
            ->method('styleSheetExists')
            ->will($this->returnValue(true));

        $this->dependencyManager->setFileSystem($this->fileSystem);

        $this->dependencyManager->loadPageDependencies();

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertNotNull($dependencies);

        $this->assertSame(
            array_merge(
                $this->pageStyles,
                array(
                     'pageSheet1_m.css',
                     DependencyManager::THIS_PAGE_KEY => $this->pageStyle,
                     DependencyManager::THIS_PAGE_ENHANCED_KEY => $this->pageStyleMobile
                )
            ),
            $dependencies['css']
        );
    }

    public function testMobileThemeAddedToBaseWhenMobile()
    {
        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::MOBILE_ENHANCEMENT));

        $this->fileSystem->expects($this->once())
            ->method('styleSheetExists')
            ->will($this->returnValue(true));

        $this->dependencyManager->setSiteTheme(self::SITE_THEME);

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                 DependencyManager::SITE_THEME_KEY => self::SITE_THEME,
                 DependencyManager::SITE_THEME_ENHANCED_KEY => self::SITE_THEME_MOBILE
            ),
            $dependencies['css']
        );
    }

    public function testDesktopThemeAddedToBaseWhenDesktop()
    {
        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::DESKTOP_ENHANCEMENT));

        $this->fileSystem->expects($this->once())
            ->method('styleSheetExists')
            ->will($this->returnValue(true));

        $this->dependencyManager->setSiteTheme(self::SITE_THEME);

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                 DependencyManager::SITE_THEME_KEY => self::SITE_THEME,
                 DependencyManager::SITE_THEME_ENHANCED_KEY => self::SITE_THEME_DESKTOP
            ),
            $dependencies['css']
        );
    }

    public function testAdminDependenciesHandledJustLikeOtherPages()
    {
        $this->request->expects($this->any())
            ->method('isAdmin')
            ->will($this->returnValue(true));
        $this->request->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue('someOtherAction'));
        $this->dependencyManager->setRequest($this->request);

        $this->dependencyManager->setPageDependenciesLists($this->dependencyDefinition);

        $this->dependencyManager->loadPageDependencies();

        $dependencies = $this->dependencyManager->getDependencies();

        $this->markTestIncomplete('Why does the verification of the above expectations not fail?');

//        $this->assertSame(
//            array(
//                 DependencyManager::THIS_PAGE_KEY => $this->pageStyle
//            ),
//            $dependencies['css']
//        );
    }
}
