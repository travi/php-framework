<?php

use travi\framework\http\Session;

class SessionTest extends PHPUnit_Framework_TestCase
{
    /** @var Session */
    private $session;

    public function setUp()
    {
        $this->session = new Session();
    }

    public function tearDown()
    {
        unset($_SESSION[Session::LOGGED_IN_USER]);
    }

    public function testDebugModeReturnsFalseForNow()
    {
        $this->assertFalse($this->session->isDebug());
    }

    public function testLogoutUserUnsetsUserInSession()
    {
        $_SESSION[Session::LOGGED_IN_USER] = 'something';

        $this->session->logOutUser();

        $this->assertNull($_SESSION[Session::LOGGED_IN_USER]);
    }
}
