<?php

use travi\framework\dependencyManagement\DependencyManager,
    travi\framework\dependencyManagement\ClientDependencies,
    travi\framework\components\Forms\Form,
    travi\framework\http\Request;

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
    private $pageStyle = '/resources/page.css';
    private $pageStyleMobile = '/resources/page_m.css';
    const SITE_THEME = 'siteTheme.css';
    const SITE_THEME_MOBILE = 'siteTheme_m.css';
    const SITE_THEME_DESKTOP = 'siteTheme_d.css';
    private $environmentUtility;
    private $request;

    private $commonInit = 'some common initialization';
    private $mobileInit = 'some device specific initialization';

    private $dependencyDefinition;
    const SOME_ADMIN_ACTION = 'someAdminAction';
    const SOME_ADMIN_CONTROLLER = 'someAdminController';
    private $otherAction = 'someOtherAction';
    public $session;


    /** @var FileSystem */
    private $fileSystem;
    /** @var DependencyManager */
    private $dependencyManager;

    public function setUp()
    {
        $this->fileSystem = $this->getMock('travi\\framework\\utilities\\FileSystem');
        $this->environmentUtility = $this->getMock('travi\\framework\\utilities\\Environment');
        $this->request = $this->getMock('travi\\framework\\http\\Request');
        $this->clientDependencyDefinitions = $this->getMock(
            'travi\\framework\\dependencyManagement\\ClientDependencies'
        );
        $this->session = $this->getMockBuilder('travi\\framework\\http\\Session')
                                ->disableOriginalConstructor()
                                ->getMock();

        $this->dependencyManager = new DependencyManager();
        $this->dependencyManager->setClientDependencyDefinitions(new ClientDependencies());
        $this->dependencyManager->setFileSystem($this->fileSystem);
        $this->dependencyManager->setRequest($this->request);
        $this->dependencyManager->setClientDependencyDefinitions($this->clientDependencyDefinitions);
        $this->dependencyManager->setEnvironmentUtility($this->environmentUtility);
        $this->dependencyManager->setSession($this->session);

        $this->request->expects($this->any())
            ->method('getController')
            ->will($this->returnValue($this->anyController));
        $this->request->expects($this->any())
            ->method('getAction')
            ->will($this->returnValue($this->anyAction));

        $anyPageDependencies = array(
            $this->anyAction => array(
                'small' => array(
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
                 strtolower(self::SOME_ADMIN_CONTROLLER) => array(
                     self::SOME_ADMIN_ACTION => array(
                         'pageStyle' => $this->pageStyle
                     )
                 )
             )
        );
    }

    public function testLoadPageDependenciesAddsFromList()
    {
        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with('pageSheet1.css')
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with($this->pageStyle)
            ->will($this->returnValue(true));

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

    public function testSiteDependenciesAddedEvenIfPageDoesNotHaveExtra()
    {

        $siteDeps = array(
            'js' => array('script1.js', 'script2.js'),
            'css' => array('sheet1.css', 'sheet2.css')
        );
        $this->dependencyManager->setPageDependenciesLists(
            array(
                 'site' => $siteDeps
            )
        );

        $this->dependencyManager->loadPageDependencies();

        $this->assertSame(
            $siteDeps['js'],
            $this->dependencyManager->getScripts()
        );
        $this->assertSame(
            $siteDeps['css'],
            $this->dependencyManager->getStyleSheets()
        );
    }

    public function testThatSiteDependenciesAreLoadedBeforePageDependencies()
    {
        $siteDeps = array(
            'js' => array('script1.js', 'script2.js'),
            'css' => array('sheet1.css', 'sheet2.css')
        );
        $dynamicDeps = array(
            'js' => array('dynamic.js'),
            'css' => array('dynamic.css')
        );
        $this->dependencyManager->setPageDependenciesLists(
            array(
                'site' => $siteDeps
            )
        );
        $this->dependencyManager->addDependencies($dynamicDeps);

        $this->dependencyManager->loadPageDependencies();

        $this->assertEquals(
            array_merge($siteDeps['js'], $dynamicDeps['js']),
            $this->dependencyManager->getScripts()
        );
        $this->assertEquals(
            array_merge($siteDeps['css'], $dynamicDeps['css']),
            $this->dependencyManager->getStyleSheets()
        );
    }

    public function testPageStyleNotSetIfEmpty()
    {
        $this->fileSystem->expects($this->once())
            ->method('styleSheetExists')
            ->with('pageSheet1.css')
            ->will($this->returnValue(true));

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
        $pathToPageStyle = 'path to page style';

        $this->fileSystem->expects($this->once())
            ->method('styleSheetExists')
            ->with($pathToPageStyle)
            ->will($this->returnValue(true));

        $pageStyle = $pathToPageStyle;

        $this->fileSystem->expects($this->once())
            ->method('getPageStyleByConvention')
            ->will($this->returnValue($pageStyle));

        $this->dependencyManager->loadPageDependencies();

        $this->assertSame($pageStyle, $this->dependencyManager->getPageStyle());
    }

    public function testPageStyleFollowsBaseFormSheet()
    {
        $pathToFormSheet = '/resources/thirdparty/travi-styles/css/travi-form.css';

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with($this->pageStyle)
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with($pathToFormSheet)
            ->will($this->returnValue(true));

        $this->dependencyManager->setPageStyle($this->pageStyle);
        $this->dependencyManager->resolveContentDependencies(array(new Form(array())));

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                $pathToFormSheet,
                DependencyManager::THIS_PAGE_KEY => $this->pageStyle
            ),
            $dependencies['css']
        );
    }

    public function testPageStyleFollowsSiteTheme()
    {
        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with($this->pageStyle)
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME)
            ->will($this->returnValue(true));

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
        $pathToFormSheet = '/resources/thirdparty/travi-styles/css/travi-form.css';

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME)
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with($pathToFormSheet)
            ->will($this->returnValue(true));

        $this->dependencyManager->setSiteTheme(self::SITE_THEME);
        $this->dependencyManager->resolveContentDependencies(array(new Form(array())));

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                $pathToFormSheet,
                DependencyManager::SITE_THEME_KEY => self::SITE_THEME
            ),
            $dependencies['css']
        );
    }

    public function testReturnsMinifiedFormOfJsAndCssWhenNotLocal()
    {
        $script = "/resources/js/some script";
        $sheet = "/resources/css/some sheet";

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with($sheet)
            ->will($this->returnValue(true));

        $this->environmentUtility->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(false));

        $this->dependencyManager->addStyleSheet($sheet);
        $this->dependencyManager->addJavaScript($script);
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();

        $this->assertEquals($dependencies['js'], array('/resources' . DependencyManager::MIN_DIR . '/js/some script'));
        $this->assertEquals($dependencies['css'], array('/resources' . DependencyManager::MIN_DIR . '/css/some sheet'));
    }

    public function testReturnsFullSourceFormOfJsAndCssWhenLocal()
    {
        $script = "/js/some script";
        $sheet = "/css/some sheet";

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with($sheet)
            ->will($this->returnValue(true));

        $this->environmentUtility->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(true));

        $this->dependencyManager->addStyleSheet($sheet);
        $this->dependencyManager->addJavaScript($script);
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();

        $this->assertEquals($dependencies['js'], array($script));
        $this->assertEquals($dependencies['css'], array($sheet));
    }

    public function testReturnsFullSourceFormOfJsAndCssWhenDebug()
    {
        $script = "/js/some script";
        $sheet = "/css/some sheet";

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with($sheet)
            ->will($this->returnValue(true));

        $this->environmentUtility->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(false));

        $this->session->expects($this->once())
            ->method('isDebug')
            ->will($this->returnValue(true));

        $this->dependencyManager->addStyleSheet($sheet);
        $this->dependencyManager->addJavaScript($script);
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();

        $this->assertEquals($dependencies['js'], array($script));
        $this->assertEquals($dependencies['css'], array($sheet));
    }

    public function testJsInitSpecificToEnhancementVersionWhenIfDefinedInConfig()
    {
        $this->dependencyManager->setPageDependenciesLists($this->dependencyDefinition);

        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::SMALL_ENHANCEMENT));

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
            ->will($this->returnValue(Request::SMALL_ENHANCEMENT));

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
            ->will($this->returnValue(Request::SMALL_ENHANCEMENT));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME_MOBILE)
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME)
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
            ->will($this->returnValue(Request::LARGE_ENHANCEMENT));

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME)
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME_DESKTOP)
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

    public function testBaseIndexedStyleNotAddedIfDoesNotExist()
    {
        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::LARGE_ENHANCEMENT));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with('siteTheme_d.css')
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with(self::SITE_THEME)
            ->will($this->returnValue(false));

        $this->dependencyManager->setSiteTheme(self::SITE_THEME);

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                 DependencyManager::SITE_THEME_ENHANCED_KEY => self::SITE_THEME_DESKTOP
            ),
            $dependencies['css']
        );

    }

    public function testBaseNonIndexedStyleNotAddedIfDoesNotExist()
    {
        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::LARGE_ENHANCEMENT));

        $this->fileSystem->expects($this->at(1))
            ->method('styleSheetExists')
            ->with('/resources/page_d.css')
            ->will($this->returnValue(true));

        $this->fileSystem->expects($this->at(0))
            ->method('styleSheetExists')
            ->with($this->pageStyle)
            ->will($this->returnValue(false));

        $this->dependencyManager->addStyleSheet($this->pageStyle);

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                '/resources/page_d.css'
            ),
            $dependencies['css']
        );

    }

    public function testRemoteFileAddedEvenThoughItDoesNotExistLocally()
    {
        $remoteFile = '//path/to/some/remote/file';

        $this->request->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::LARGE_ENHANCEMENT));

        $this->dependencyManager->addStyleSheet($remoteFile);

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                $remoteFile
            ),
            $dependencies['css']
        );
    }

    public function testAdminDependenciesHandledJustLikeOtherPages()
    {
        $request = $this->getMock('travi\\framework\\http\\Request');
        $request->expects($this->once())
            ->method('isAdmin')
            ->will($this->returnValue(true));
        $request->expects($this->once())
            ->method('getController')
            ->will($this->returnValue(self::SOME_ADMIN_CONTROLLER));
        $request->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue(self::SOME_ADMIN_ACTION));
        $this->dependencyManager->setRequest($request);

        $this->fileSystem->expects($this->once())
            ->method('styleSheetExists')
            ->will($this->returnValue(true));

        $this->dependencyManager->setPageDependenciesLists($this->dependencyDefinition);

        $this->dependencyManager->loadPageDependencies();

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertSame(
            array(
                 DependencyManager::THIS_PAGE_KEY => $this->pageStyle
            ),
            $dependencies['css']
        );
    }

    public function testEmptyStringNotAddedWhenReturnedAsDependency()
    {
        $widget = 'someWidgetWithDeps';

        $request = $this->getMock('travi\\framework\\http\\Request');
        $request->expects($this->once())
            ->method('isAdmin')
            ->will($this->returnValue(false));
        $request->expects($this->once())
            ->method('getController')
            ->will($this->returnValue($this->anyController));
        $request->expects($this->once())
            ->method('getAction')
            ->will($this->returnValue($this->otherAction));
        $this->dependencyManager->setRequest($request);

        $jsDependency = '/some/dep/that/isnt/defined/as/a/component';
        $this->clientDependencyDefinitions->expects($this->at(1))
            ->method('getDependenciesFor')
            ->with($widget)
            ->will(
                $this->returnValue(
                    array(
                        'jsDependencies' => array(
                            $jsDependency
                        )
                    )
                )
            );

        $this->dependencyManager->setPageDependenciesLists(
            array(
                strtolower($this->anyController) => array(
                    $this->otherAction => array(
                        'js' => array(
                            $widget
                        )
                    )
                )
            )
        );

        $this->dependencyManager->loadPageDependencies();

        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertEquals(array($jsDependency), $dependencies['js']);
    }

    public function testThatEmptyListsReturnedIfNoDependenciesToList()
    {
        $dependencies = $this->dependencyManager->getDependencies();

        $this->assertEmpty(array(), $dependencies['css']);
        $this->assertEquals(array(), $dependencies['js']);
        $this->assertEquals(array(), $dependencies['clientTemplates']);
    }

    public function testThatCriticalJsListIncludesModernizr()
    {
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();

        $this->assertEquals(array(), $dependencies['criticalJs']);
    }
}
