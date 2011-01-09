<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/src/http/Request.class.php';

/**
 * Test class for Request.
 * Generated by PHPUnit on 2011-01-09 at 14:13:40.
 */
class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $request = new Request;
        $request->setURI('/about/webmaster');
        $request->setRequestMethod('GET');

        $this->request = $request;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
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

    public function testGetAction()
    {
        $this->assertSame(false, $this->request->isAdmin());
        $this->assertSame('webmaster', $this->request->getAction());
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
}
?>