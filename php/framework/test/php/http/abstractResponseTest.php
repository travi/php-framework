<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../objects/page/abstractResponse.class.php';
require_once dirname(__FILE__).'/../../../src/dependencyManagement/DependencyManager.class.php';

class AbstractResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractResponse
     */
    protected $object;

    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass('AbstractResponse');
    }

    public function testSiteName()
    {
        $siteName = 'siteName';
        $this->object->setSiteName($siteName);
        $this->assertSame($siteName, $this->object->getSiteName());
    }

    public function testTitle()
    {
        $title = 'title';
        $this->object->setTitle($title);
        $this->assertSame($title, $this->object->getTitle());
    }

    public function testTitleWithSiteName()
    {
        $title = 'title';
        $siteName = 'siteName';
        $this->object->setSiteName($siteName);
        $this->object->setTitle($title);
        $this->assertSame($title.' | '.$siteName, $this->object->getDecoratedTitle());
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
        $this->object->setSiteName($siteName);
        $this->object->setSiteHeader($siteHeader);
        $this->assertSame($siteHeader, $this->object->getHeader());
    }

    public function testSiteHeaderLeftBlank()
    {
        $siteHeader = 'siteHeader';
        $siteName = 'siteName';
        $this->object->setSiteName($siteName);
        $this->object->setSiteHeader();
        $this->assertSame($siteName, $this->object->getHeader());
    }

    public function testSubHeader()
    {
        $subHeader = 'subHeader';
        $this->object->setSubHeader($subHeader);
        $this->assertSame($subHeader, $this->object->getSubHeader());
    }

    public function testSetContent()
    {
        $content = 'content';
        $this->object->setContent($content);
        $this->assertSame($content, $this->object->getContent());
    }

    public function testAddToResponse()
    {
        $content = 'content';
        $description = 'description';
        $this->object->addToResponse($description, $content);
        $this->assertSame(array($description => $content), $this->object->getContent());
    }

    public function testAddLinkTag()
    {
        $link = 'link';
        $rel = 'rel';
        $title = 'title';
        $type = 'type';

        $this->object->addLinkTag($link, $rel, $title, $type);
        $this->assertSame(
            array(
                 array(
                     'link'  => $link,
                     'title' => $title,
                     'type'  => $type,
                     'rel'   => $rel
                 )
            ),
            $this->object->getLinkTags()
        );
    }

    public function testAddFeed()
    {
        $feed = 'feed';
        $title = 'title';
        $this->object->addFeed($feed, $title);
        $this->assertSame(
            array(
                 array(
                     'link'  => $feed,
                     'title' => $title,
                     'type'  => 'application/rss+xml',
                     'rel'   => 'alternate'
                 )
            ),
            $this->object->getLinkTags()
        );
    }

    public function testAddFeedNoTitle()
    {
        $feed = 'feed';
        $this->object->addFeed($feed);
        $this->assertSame(
            array(
                 array(
                     'link'  => $feed,
                     'title' => 'RSS',
                     'type'  => 'application/rss+xml',
                     'rel'   => 'alternate'
                 )
            ),
            $this->object->getLinkTags()
        );
    }

    public function testMetaTag()
    {
        $tag = 'tag';
        $this->object->addMetaTag($tag);
        $this->assertSame(
            array('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />',$tag),
            $this->object->getMetaTags()
        );
    }

    /**
     * @todo refactor to that the Navigation object is initialized
     */
    public function testSetPrimaryNav()
    {
        //        $nav = array('text1' => 'link1', 'text2' => 'link2');
        //        $this->object->setPrimaryNav($nav);
        //        $this->assertSame($nav, $this->object->getNav());
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetMainNav().
     */
    public function testGetPrimaryNav()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testSetSubNav().
     */
    public function testSetSubNav()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testGetSubNav().
     */
    public function testGetSubNav()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
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
        $this->object->setLayoutTemplate($template);
        $this->assertSame($template, $this->object->getLayoutTemplate());
    }

    public function testSetPageTemplate()
    {
        $template = 'template';
        $this->object->setPageTemplate($template);
        $this->assertSame($template, $this->object->getPageTemplate());
    }

    public function testClientTemplate()
    {
        $template = 'template';
        $name = 'name';
        $this->object->addClientTemplate($name, $template);
        $this->assertSame(array($name => $template), $this->object->getClientTemplates());
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
     * @todo Implement testGoog_analytics().
     */
    public function testGoog_analytics()
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
