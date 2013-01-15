<?php

use Travi\framework\http\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    /** @var Request */
    protected $request;

    protected function setUp()
    {
        $request = new Request;
        $request->setURI('/about/webmaster');
        $request->setRequestMethod('GET');

        $this->request = $request;
    }

    public function testGetController()
    {
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame('about', $this->request->getController());
    }

    public function testRoot()
    {
        $this->request->setURI('/');
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame('home', $this->request->getController());
    }

    public function testPathFilter()
    {
        $controller = 'images';
        $this->request->setURI('/albums/1234/' . $controller . '/');
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame($controller, $this->request->getController());
    }

    public function testPathFilterWithTrailingSlash()
    {
        $controller = 'images';
        $filter = 'albums';
        $filterId = '1234';
        $filters = array();
        $filters[$filter] = $filterId;

        $this->request->setURI('/' . $filter . '/' . $filterId . '/' . $controller);
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame($controller, $this->request->getController());
        $this->assertEquals($filters, $this->request->getFilters());
    }

    public function testGetAction()
    {
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame('webmaster', $this->request->getAction());
    }

    public function testGetActionDoesNotReturnQueryParameter()
    {
        $this->request->setURI('/about/?someQueryParameter=someValue');
        $this->assertSame('index', $this->request->getAction());
    }

    public function testControllerRoot()
    {
        $this->request->setURI('/about/');
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame('index', $this->request->getAction());
    }

    public function testGetRequestMethod()
    {
        $this->assertSame('GET', $this->request->getRequestMethod());
    }

    public function testIsNotAdmin()
    {
        $this->assertSame(false, $this->request->isAdmin());
    }

    public function testIsAdmin()
    {
        $this->request->setURI('/admin/about/webmaster');
        $this->assertSame(true, $this->request->isAdmin());
    }

    public function testAdminRoot()
    {
        $this->request->setURI('/admin/');
        $this->assertSame(true, $this->request->isAdmin());
        $this->assertSame('home', $this->request->getController());
    }

    public function testGetAdminController()
    {
        $this->request->setURI('/admin/about/webmaster');
        $this->assertSame(true, $this->request->isAdmin());
        $this->assertSame('about', $this->request->getController());
    }

    public function testGetAdminAction()
    {
        $this->request->setURI('/admin/about/webmaster');
        $this->assertSame(true, $this->request->isAdmin());
        $this->assertSame('webmaster', $this->request->getAction());
    }

    public function testKeyDefinedProperlyForEnhancementVersionKey()
    {
        $this->assertSame('enhancementVersion', Request::ENHANCEMENT_VERSION_KEY);
    }

    public function testKeyDefinedProperlyForBaseEnhancementVersion()
    {
        $this->assertSame('base', Request::BASE_ENHANCEMENT);
    }

    public function testKeyDefinedProperlyForMobileEnhancementVersion()
    {
        $this->assertSame('mobile', Request::MOBILE_ENHANCEMENT);
    }

    public function testKeyDefinedProperlyForDesktopEnhancementVersion()
    {
        $this->assertSame('desktop', Request::DESKTOP_ENHANCEMENT);
    }

    public function testBaseReturnedAsEnhancementVersionWhenCookieNotSet()
    {
        $this->request->setEnhancementVersion('');
        $this->assertEquals(Request::BASE_ENHANCEMENT, $this->request->getEnhancementVersion());
    }

    public function testVersionFromCookieReturnedAsEnhancementVersion()
    {
        $this->request->setEnhancementVersion(Request::DESKTOP_ENHANCEMENT);
        $this->assertEquals(Request::DESKTOP_ENHANCEMENT, $this->request->getEnhancementVersion());
    }
}
