<?php

use travi\framework\http\Request;

class RequestTest extends PHPUnit_Framework_TestCase
{
    /** @var Request */
    protected $request;

    protected function setUp()
    {
        $request = new Request;
        $request->setRequestMethod('GET');

        $this->request = $request;
    }

    public function tearDown()
    {
        $this->request = null;
    }

    public function testThatFullUriIsAvailable()
    {
        $uri = 'some-url';
        $this->request->setURI($uri);

        $this->assertEquals($uri, $this->request->getUri());
    }

    public function testGetController()
    {
        $this->request->setURI('/about/webmaster');

        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame('about', $this->request->getController());
    }

    public function testThatRestfulPartsMappedProperlyWhenIdIsLastPart() {
        $controller = 'entities';
        $id = 1234;
        $this->request->setURI($controller . '/' . $id);

        $this->assertEquals($controller, $this->request->getController());
        $this->assertEquals('index', $this->request->getAction());
        $this->assertEquals($id, $this->request->getId());
    }

    public function testThatRestfulPartsMappedProperlyWhenActionIsLastPart()
    {
        $controller = 'entities';
        $action = 'edit';
        $id = 1234;
        $this->request->setURI($controller . '/' . $id . '/' . $action);

        $this->assertEquals($controller, $this->request->getController());
        $this->assertEquals($action, $this->request->getAction());
        $this->assertEquals($id, $this->request->getId());
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
        $this->assertSame('index', $this->request->getAction());
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
        $this->request->setURI('/about/webmaster');

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
        $this->request->setURI('/about/webmaster');

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
        $this->assertSame('ev', Request::ENHANCEMENT_VERSION_KEY);
    }

    public function testKeyDefinedProperlyForBaseEnhancementVersion()
    {
        $this->assertSame('base', Request::BASE_ENHANCEMENT);
    }

    public function testKeyDefinedProperlyForMobileEnhancementVersion()
    {
        $this->assertSame('small', Request::SMALL_ENHANCEMENT);
    }

    public function testCookieValuesSetProperly()
    {
        $this->assertEquals('s', Request::SMALL_COOKIE_VALUE);
        $this->assertEquals('l', Request::LARGE_COOKIE_VALUE);
    }

    public function testKeyDefinedProperlyForDesktopEnhancementVersion()
    {
        $this->assertSame('large', Request::LARGE_ENHANCEMENT);
    }

    public function testBaseReturnedAsEnhancementVersionWhenCookieNotSet()
    {
        $this->request->setEnhancementVersion('');
        $this->assertEquals(Request::BASE_ENHANCEMENT, $this->request->getEnhancementVersion());
    }

    public function testLargeVersionFromCookieReturnedAsCorrectEnhancementVersion()
    {
        $this->request->setEnhancementVersion('l');
        $this->assertEquals(Request::LARGE_ENHANCEMENT, $this->request->getEnhancementVersion());
    }

    public function testSmallVersionFromCookieReturnedAsCorrectEnhancementVersion()
    {
        $this->request->setEnhancementVersion('s');
        $this->assertEquals(Request::SMALL_ENHANCEMENT, $this->request->getEnhancementVersion());
    }
}
