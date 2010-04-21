<?php
require_once 'PHPUnit/Extensions/SeleniumTestCase.php';
 
class QUnit extends PHPUnit_Extensions_SeleniumTestCase
{
	public static $browsers = array(
		array(
			'name' 		=> 'Firefox',
			'browser'	=> '*firefox'
		),
		array(
			'name'		=> 'Safari',
			'browser'	=> '*safari'
//		),
//		array(
//			'name'		=> 'Chrome',
//			'browser'	=> '*googlechrome'
//		),
//		array(
//			'name'		=> 'Opera',
//			'browser'	=> '*opera'
		)
	);
	
    protected function setUp()
    {
//        $this->setBrowser('*firefox');
        $this->setBrowserUrl('file:///home/travi/travi.org/test/js/testrunners/');
    }
 
    public function testFailures()
    {
        $this->open('announcementsPageTest.html');
        $this->assertTitle('Announcements Page Test');
        $this->waitForElementPresent('css=#qunit-testresult .failed');
        $this->assertText('css=#qunit-testresult .failed', 'exact:0');
        $this->assertEquals($this->getText('css=#qunit-testresult .passed'), $this->getText('css=#qunit-testresult .total'));
    }
}
?>