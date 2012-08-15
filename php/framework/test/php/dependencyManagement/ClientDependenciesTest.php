<?php
require_once dirname(__FILE__) . '/../../../src/http/Request.class.php';


class ClientDependenciesTest extends PHPUnit_Framework_TestCase
{
    private $dependencyDefinition;
    const SOME_COMPONENT = 'someComponent';
    const PATH_TO_SOME_COMPONENT = 'somePath';
    const SOME_CSS_FILE = 'someCssFile.css';
    const SOME_JS_FILE = 'someJsFile.js';

    /** @var ClientDependencies */
    public $dependencies;

    public function setUp()
    {
        $this->dependencyDefinition = array(
            self::SOME_COMPONENT     => array(
                'local' => self::PATH_TO_SOME_COMPONENT
            )
        );

        $this->dependencies = new ClientDependencies();
        $this->dependencies->setRequest(new Request());
        $this->dependencies->setUiDeps($this->dependencyDefinition);
    }

    public function testFileUriResolvedForComponent()
    {
        $this->assertEquals(
            self::PATH_TO_SOME_COMPONENT,
            $this->dependencies->resolveFileURI(self::SOME_COMPONENT)
        );
    }

    public function testDependenciesReturnedForComponent()
    {
        $this->assertEquals(
            array(
                'local'             => self::PATH_TO_SOME_COMPONENT,
                'cdn'               => ''
            ),
            $this->dependencies->getDependenciesFor(self::SOME_COMPONENT)
        );
    }

    /**
     * @expectedException           MissingLocalPathToResourceException
     * @expectedExceptionMessage    Local URI required for invalidComponent
     */
    public function testComponentWithoutLocalPathThrowsException()
    {
        $this->dependencies->setUiDeps(
            array(
                'invalidComponent'  => array()
            )
        );

        $this->dependencies->resolveFileURI('invalidComponent');
    }

    public function testDesktopSpecificDependencyMapping()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->once())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::DESKTOP_ENHANCEMENT));
        $this->dependencies->setRequest($mockRequest);

        $this->dependencies->setUiDeps(
            array(
                self::SOME_COMPONENT    => array(
                    'desktop'   =>  array(
                        'local' =>  self::PATH_TO_SOME_COMPONENT,
                        'jsDependencies'    => array(
                            'jquery'
                        )
                    )
                )
            )
        );

        $this->assertEquals(
            self::PATH_TO_SOME_COMPONENT,
            $this->dependencies->resolveFileURI(self::SOME_COMPONENT)
        );
    }

    public function testBaseDependencyMappingWhenEnhancementDefined()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->once())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::DESKTOP_ENHANCEMENT));
        $this->dependencies->setRequest($mockRequest);

        $this->dependencies->setUiDeps(
            array(
                self::SOME_COMPONENT    => array(
                    'cssDependencies' => array(
                        self::SOME_CSS_FILE
                    ),
                    'jsDependencies' => array(
                        self::SOME_JS_FILE
                    ),
                    'desktop'   =>  array(
                        'local' =>  self::PATH_TO_SOME_COMPONENT,
                        'jsDependencies'    => array(
                            'jquery'
                        )
                    )
                )
            )
        );

        $this->assertEquals(
            array(
                'local' => self::PATH_TO_SOME_COMPONENT,
                'cdn' => '',
                'jsDependencies' => array(
                    self::SOME_JS_FILE,
                    'jquery'
                ),
                'cssDependencies' => array(
                    self::SOME_CSS_FILE
                )
            ),
            $this->dependencies->getDependenciesFor(self::SOME_COMPONENT)
        );
    }

    public function testPluginsIncludeParentAsDependency()
    {
        $mockRequest = $this->getMock('Request');
        $mockRequest->expects($this->any())
            ->method('getEnhancementVersion')
            ->will($this->returnValue(Request::DESKTOP_ENHANCEMENT));
        $this->dependencies->setRequest($mockRequest);

        $this->dependencies->setUiDeps(
            array(
                self::SOME_JS_FILE    => array(
                    'local' => self::SOME_JS_FILE,
                    'plugins' => array(
                        self::SOME_COMPONENT => array(
                            'local' => self::PATH_TO_SOME_COMPONENT,
                            'cssDependencies' => array(
                                self::SOME_CSS_FILE
                            )
                        )
                    )
                )
            )
        );

        $this->assertEquals(
            array(
                'local' => self::PATH_TO_SOME_COMPONENT,
                'cdn' => '',
                'jsDependencies' => array(
                    self::SOME_JS_FILE
                ),
                'cssDependencies' => array(
                    self::SOME_CSS_FILE
                )
            ),
            $this->dependencies->getDependenciesFor(self::SOME_COMPONENT)
        );
    }
}