<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../src/http/Response.class.php';

class ResponseTest extends PHPUnit_Framework_TestCase
{
    private $someTitle = "some title";
    private $someSiteName = 'some site name';
    /**
     * @var Response
     */
    protected $response;

    protected function setUp()
    {
        $this->response = new Response;
        $this->response->setSiteName($this->someSiteName);
    }

    public function testTitle()
    {
        $this->response->setTitle($this->someTitle);

        $this->assertSame($this->someTitle, $this->response->getTitle());
    }

    public function testDecoratedTitle()
    {
        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }

    /**
     * cannot test these two properly because of the use of constants
     *
    public function testDecoratedTitleDevEnvironment() {
        define('ENV', 'development');
        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            '[dev] ' . $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }

    public function testDecoratedTitleTestEnvironment() {
        define('ENV', 'test');
        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            '[test] ' . $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }
     */

    public function testTagLine()
    {
        $this->response->setTagLine('tagLine');

        $this->assertSame('tagLine', $this->response->getTagLine());
    }

    /**
     * @todo Implement testRespond().
     */
    public function testRespond()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testLoadPageDependenciesAddsFromList()
    {
        $anyController = 'testController';
        $anyAction = 'testAction';
        $jsDeps = array(
            'dep1',
            'dep2',
            'dep3'
        );
        $pageStyles = array(
            'page style sheet'
        );
        $siteWidgets = array(
            'siteWidget'
        );

        $this->response->setConfig(
            array(
                 'uiDeps' => array(
                     'pages' => array(
                         'site' => array(
                             'js' => $siteWidgets
                         ),
                         strtolower($anyController) => array(
                             $anyAction => array(
                                 'js' => $jsDeps,
                                 'css' => $pageStyles
                             )
                         )
                     )
                 )
            )
        );

        $this->response->loadPageDependencies($anyController, $anyAction);

        $this->assertSame(
            array_merge($siteWidgets, $jsDeps),
            $this->response->getDependencyList('js')
        );
        $this->assertSame(
            array_merge($pageStyles),
            $this->response->getDependencyList('css')
        );
    }
}
?>
