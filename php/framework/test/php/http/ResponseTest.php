<?php

use Travi\framework\page\AbstractResponse,
    Travi\framework\http\Response,
    Travi\framework\http\Request,
    Travi\framework\utilities\Environment;

class ResponseTest extends PHPUnit_Framework_TestCase
{
    private $someTitle = "some title";
    private $someSiteName = 'some site name';
    private $jsDeps = array(
        'dep1',
        'dep2',
        'dep3'
    );
    private $pageStyles = array(
        'page style sheet'
    );
    private $pageStyle = 'page.css';
    private $siteWidgets = array(
        'siteWidget'
    );
    private $anyController = 'testController';
    private $anyAction = 'testAction';

    /** @var Environment */
    private $environment;

    /** @var Response */
    private $response;
    /** @var Request */
    private $request;

    protected function setUp()
    {
        $prodUrl = 'something.com';

        $this->request = $this->getMock('Travi\\framework\\http\\Request');

        $this->response = new Response();
        $this->response->setRequest($this->request);
        $this->response->init(array());
        $this->response->setSiteName($this->someSiteName);

        $this->environment = $this->getMock('Travi\\framework\\utilities\\Environment');
        $this->response->setEnvironment($this->environment);
    }

    public function testTitle()
    {
        $this->response->setTitle($this->someTitle);

        $this->assertSame($this->someTitle, $this->response->getTitle());
    }

    public function testTitleWithSiteName()
    {
        $this->environment->expects($this->any())
            ->method('isProduction')
            ->will($this->returnValue(true));

        $title = 'title';
        $siteName = 'siteName';
        $this->response->setSiteName($siteName);
        $this->response->setTitle($title);
        $this->assertSame($title.' | '.$siteName, $this->response->getDecoratedTitle());
    }

    public function testDecoratedTitle()
    {
        $this->environment->expects($this->any())
            ->method('isProduction')
            ->will($this->returnValue(true));

        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }

    public function testDecoratedTitleProdEnvironment()
    {
        $this->environment->expects($this->any())
            ->method('isProduction')
            ->will($this->returnValue(true));

        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }

    public function testDecoratedTitleDevEnvironment()
    {
        $this->environment->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(true));
        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            '[dev] ' . $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }

    public function testDecoratedTitleTestEnvironment()
    {
        $this->environment->expects($this->once())
            ->method('isLocal')
            ->will($this->returnValue(false));
        $this->environment->expects($this->once())
            ->method('isProduction')
            ->will($this->returnValue(false));
        $this->response->setTitle($this->someTitle);

        $this->assertSame(
            '[test] ' . $this->someTitle . ' | ' . $this->someSiteName,
            $this->response->getDecoratedTitle()
        );
    }

    public function testMethodNotAllowedDefinedProperly()
    {
        $this->assertEquals('405 Method Not Allowed', Response::NOT_ALLOWED);
    }

    public function testMethodNotImplementedDefinedProperly()
    {
        $this->assertEquals('501 Not Implemented', Response::NOT_IMPLEMENTED);
    }

    public function testSettingStatusAddsHeaderAndSetsPageTemplate()
    {
        /** @var $response Response */
        $response = $this->getMock(
            'ResponseShunt',
            array(
                'setHeader',
                'setPageTemplate',
                'addToResponse'
            )
        );
        $response->expects($this->once())
            ->method('setHeader')
            ->with('HTTP/1.1 ' . Response::NOT_ALLOWED);
        $response->expects($this->once())
            ->method('setPageTemplate')
            ->with('../status/status.tpl');
        $response->expects($this->once())
            ->method('addToResponse')
            ->with('status', Response::NOT_ALLOWED);

        $response->setStatus(Response::NOT_ALLOWED);
    }

    public function testSettingDefinedStatusesDoNotThrowException()
    {
        $response = new ResponseShunt(array());

        $response->setStatus(Response::NOT_ALLOWED);
        $response->setStatus(Response::NOT_IMPLEMENTED);
    }

    /**
     * @expectedException Travi\framework\exception\InvalidHttpStatusException
     */
    public function testSettingUnDefinedStatusThrowsException()
    {
        $response = new ResponseShunt(array());

        $response->setStatus('some random status string');
    }

    public function testTagLine()
    {
        $this->response->setTagLine('tagLine');

        $this->assertSame('tagLine', $this->response->getTagLine());
    }

    public function testRssTypeDefinition()
    {
        $this->assertSame('application/rss+xml', AbstractResponse::LINK_ATTR_RSS_TYPE);
    }

    public function testRelAttrOptionDefinition()
    {
        $this->assertSame('alternate', AbstractResponse::LINK_ATTR_REL_ALTERNATE);
    }

    public function testSiteFeed()
    {
        $this->response->defineSiteFeed('http://travi.org/rss/');

        $this->assertSame(
            $this->response->getLinkTags(),
            array(
                array(
                     'link' => 'http://travi.org/rss/',
                     'title' => Response::SITE_FEED_KEY,
                     'type' => AbstractResponse::LINK_ATTR_RSS_TYPE,
                     'rel' => AbstractResponse::LINK_ATTR_REL_ALTERNATE
                )
            )
        );
    }

    public function testSiteFeedFromConfig()
    {
        $someFeed = 'some feed';

        $response = new Response();
        $response->setRequest($this->request);
        $response->init(array('siteFeed' => $someFeed));

        $this->assertEquals(
            array(
                array(
                     'link' => $someFeed,
                     'title' => Response::SITE_FEED_KEY,
                     'type' => AbstractResponse::LINK_ATTR_RSS_TYPE,
                     'rel' => AbstractResponse::LINK_ATTR_REL_ALTERNATE
                )
            ),
            $response->getLinkTags()
        );
    }

    public function testPrimaryNav()
    {
        $nav = array('text1' => 'link1', 'text2' => 'link2');
        $this->response->setPrimaryNav($nav);
        $this->assertSame($nav, $this->response->getMainNav());
    }

    public function testAdminNavAddedIfAdmin()
    {
        $nav = array('text1' => 'link1', 'text2' => 'link2');

        $this->request->expects($this->once())
            ->method('isAdmin')
            ->will($this->returnValue(true));

        $this->response->setAdminNav($nav);

        $this->assertEquals($nav, $this->response->getAdminNav());
    }

    public function testAdminNavNotAddedIfNotAdmin()
    {
        $nav = array('text1' => 'link1', 'text2' => 'link2');

        $this->request->expects($this->once())
            ->method('isAdmin')
            ->will($this->returnValue(false));

        $this->response->setAdminNav($nav);

        $this->assertNull($this->response->getAdminNav());
    }

    public function testSubNav()
    {
        $subNav = array(
            array(
                'text' => 'something',
                'link' => 'some link'
            )
        );

        $this->response->setSubNav($subNav);

        $this->assertEquals($subNav, $this->response->getSubNav());
    }

    public function testProductionResolvesTrue()
    {
        $this->environment->expects($this->once())
            ->method('isProduction')
            ->will($this->returnValue(true));

        $this->assertTrue($this->response->isProduction());
    }

    public function testProductionResolvesFalseWhenNot()
    {
        $this->environment->expects($this->once())
            ->method('isProduction')
            ->will($this->returnValue(false));

        $this->assertFalse($this->response->isProduction());
    }
}

class ResponseShunt extends Response
{
    public function setHeader()
    {

    }
}