<?php

use travi\framework\page\AbstractResponse;

class AbstractResponseTest extends PHPUnit_Framework_TestCase
{
    /** @var AbstractResponse */
    protected $response;

    protected function setUp()
    {
        $this->response = $this->getMockForAbstractClass('travi\\framework\\page\\AbstractResponse');
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
}
