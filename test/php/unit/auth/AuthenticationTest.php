<?php

use travi\framework\auth\Authentication,
    travi\framework\utilities\FileSystem;

class AuthenticationTest extends PHPUnit_Framework_TestCase
{
    /** @var Authentication */
    private $authentication;
    /** @var FileSystem */
    private $fileSystem;

    private $userName = 'some-user-name';

    private $password = 'correct-password';

    private $lines = array();


    public function setUp()
    {
        $this->authentication = new Authentication();
        $this->fileSystem = $this->getMock('travi\\framework\\utilities\\FileSystem');
        $this->authentication->setFileSystem($this->fileSystem);

        array_push($this->lines, 'some-other-user-name:' . $this->encryptPassword('some-password'));
        array_push($this->lines, $this->userName . ':' . $this->encryptPassword($this->password));
    }

    private function encryptPassword($password)
    {
        return crypt($password, base64_encode($password));
    }

    public function tearDown()
    {
        unset($_SERVER['PHP_AUTH_USER']);
        unset($_SERVER['PHP_AUTH_PW']);
    }

    /**
     * @expectedException travi\framework\exception\UnauthorizedException
     */
    public function testThrowsExceptionWhenUserHasNotProvidedCredentials()
    {
        $this->authentication->ensureAuthenticated();
    }

    public function testExceptionNotThrownWhenAuthenticated()
    {
        $this->setUpFileSystem();
        $_SERVER['PHP_AUTH_USER'] = $this->userName;
        $_SERVER['PHP_AUTH_PW'] = $this->password;

        $this->authentication->ensureAuthenticated();
    }

    /**
     * @expectedException travi\framework\exception\UnauthorizedException
     */
    public function testExceptionThrownWhenUsernamePasswordNotCorrect()
    {
        $this->setUpFileSystem();
        $_SERVER['PHP_AUTH_USER'] = $this->userName;
        $_SERVER['PHP_AUTH_PW'] = 'wrong-password';

        $this->authentication->ensureAuthenticated();
    }

    public function testCredentialsCanBePulledFromAlternateHeader()
    {
        $this->setUpFileSystem();
        $_SERVER['HTTP_AUTHORIZATION'] = 'Basic:' . base64_encode($this->userName . ':' . $this->password);

        $this->authentication->ensureAuthenticated();
    }

    private function setUpFileSystem()
    {
        $this->fileSystem->expects($this->once())
            ->method('getLinesFromFile')
            ->with('.pwd', '/config/auth/')
            ->will($this->returnValue($this->lines));
    }
}
