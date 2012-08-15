<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../../../src/dependencyManagement/DependencyManager.class.php';

class AbstractResponseTest extends PHPUnit_Framework_TestCase
{
    /** @var AbstractResponse */
    protected $response;

    protected function setUp()
    {
        $this->response = $this->getMockForAbstractClass('AbstractResponse');
    }

    public function testSiteName()
    {
        $siteName = 'siteName';
        $this->response->setSiteName($siteName);
        $this->assertSame($siteName, $this->response->getSiteName());
    }

    public function testTitle()
    {
        $title = 'title';
        $this->response->setTitle($title);
        $this->assertSame($title, $this->response->getTitle());
    }

    public function testTitleWithSiteName()
    {
        $title = 'title';
        $siteName = 'siteName';
        $this->response->setSiteName($siteName);
        $this->response->setTitle($title);
        $this->assertSame($title.' | '.$siteName, $this->response->getDecoratedTitle());
    }

    /**
     * @todo Implement testTitleDevEnv().
     */
    public function testTitleDevEnv()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testTitleTestEnv().
     */
    public function testTitleTestEnv()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testSiteHeader()
    {
        $siteHeader = 'siteHeader';
        $siteName = 'siteName';
        $this->response->setSiteName($siteName);
        $this->response->setSiteHeader($siteHeader);
        $this->assertSame($siteHeader, $this->response->getHeader());
    }

    public function testSiteHeaderLeftBlank()
    {
        $siteHeader = 'siteHeader';
        $siteName = 'siteName';
        $this->response->setSiteName($siteName);
        $this->response->setSiteHeader();
        $this->assertSame($siteName, $this->response->getHeader());
    }

    public function testSubHeader()
    {
        $subHeader = 'subHeader';
        $this->response->setSubHeader($subHeader);
        $this->assertSame($subHeader, $this->response->getSubHeader());
    }

    public function testSetContent()
    {
        $content = 'content';
        $this->response->setContent($content);
        $this->assertSame($content, $this->response->getContent());
    }

    public function testAddToResponse()
    {
        $content = 'content';
        $description = 'description';
        $this->response->addToResponse($description, $content);
        $this->assertSame(array($description => $content), $this->response->getContent());
    }

    public function testAddLinkTag()
    {
        $link = 'link';
        $rel = 'rel';
        $title = 'title';
        $type = 'type';

        $this->response->addLinkTag($link, $rel, $title, $type);
        $this->assertSame(
            array(
                 array(
                     'link'  => $link,
                     'title' => $title,
                     'type'  => $type,
                     'rel'   => $rel
                 )
            ),
            $this->response->getLinkTags()
        );
    }

    public function testAddFeed()
    {
        $feed = 'feed';
        $title = 'title';
        $this->response->addFeed($feed, $title);
        $this->assertSame(
            array(
                 array(
                     'link'  => $feed,
                     'title' => $title,
                     'type'  => 'application/rss+xml',
                     'rel'   => 'alternate'
                 )
            ),
            $this->response->getLinkTags()
        );
    }

    public function testAddFeedNoTitle()
    {
        $feed = 'feed';
        $this->response->addFeed($feed);
        $this->assertSame(
            array(
                 array(
                     'link'  => $feed,
                     'title' => 'RSS',
                     'type'  => 'application/rss+xml',
                     'rel'   => 'alternate'
                 )
            ),
            $this->response->getLinkTags()
        );
    }

    public function testMetaTag()
    {
        $tag = 'tag';
        $this->response->addMetaTag($tag);
        $this->assertSame(
            array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />',$tag),
            $this->response->getMetaTags()
        );
    }

    /**
     * @todo Implement testGetNavSection().
     */
    public function testGetNavSection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testAddNavItem().
     */
    public function testAddNavItem()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetNav().
     */
    public function testGetNav()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetSiteSection().
     */
    public function testGetSiteSection()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    public function testLayoutTemplate()
    {
        $template = 'template';
        $this->response->setLayoutTemplate($template);
        $this->assertSame($template, $this->response->getLayoutTemplate());
    }

    public function testSetPageTemplate()
    {
        $template = 'template';
        $this->response->setPageTemplate($template);
        $this->assertSame($template, $this->response->getPageTemplate());
    }

    public function testClientTemplate()
    {
        $template = 'template';
        $name = 'name';
        $this->response->addClientTemplate($name, $template);
        $this->assertSame(array($name => $template), $this->response->getClientTemplates());
    }

    /**
     * @todo Implement testDisplay().
     */
    public function testDisplay()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testRedirect().
     */
    public function testRedirect()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
