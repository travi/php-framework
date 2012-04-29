<?php
require_once dirname(__FILE__).'/../../../src/http/Session.class.php';

class SessionTest extends PHPUnit_Framework_TestCase
{
    /** @var Session */
    private $session;

    public function testDebugModeReturnsFalseForNow()
    {
        $this->session = new Session();

        $this->assertFalse($this->session->isDebug());
    }
}
