<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/src/http/Response.class.php';

/**
 * Test class for Response.
 * Generated by PHPUnit on 2011-01-10 at 20:49:47.
 */
class ResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Response
     */
    protected $response;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->response = new Response;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

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
}
?>
