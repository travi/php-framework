<?php

namespace Travi\framework\auth;

use Travi\framework\exception\UnauthorizedException;
use Travi\framework\utilities\FileSystem;

class Authentication
{
    /** @var FileSystem */
    private $fileSystem;

    public function ensureAuthenticated()
    {
        $authenticated = $this->authenticate();

        if (!$authenticated) {
            throw new UnauthorizedException();
        }
    }

    private function authenticate()
    {
        if ($this->credentialsAreProvided()) {
            return $this->verifyCredentials($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        }
        return false;
    }

    private function credentialsAreProvided()
    {
        if ($this->alternateHeaderSet()) {
            list($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']) = $this->extractCredentials();
        }

        return isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW']);
    }

    private function extractCredentials()
    {
        return explode(':', base64_decode($this->getValueFromHeader($_SERVER['HTTP_AUTHORIZATION'])));
    }

    /**
     * @param $header
     * @return string
     */
    private function getValueFromHeader($header)
    {
        return substr($header, 6);
    }

    /**
     * @return bool
     */
    private function alternateHeaderSet()
    {
        return !isset($_SERVER['PHP_AUTH_USER'])
            && !isset($_SERVER['PHP_AUTH_PW'])
            && isset($_SERVER['HTTP_AUTHORIZATION']);
    }

    private function verifyCredentials($userName, $password)
    {
        $validUsers = $this->fileSystem->getLinesFromFile('.pwd', '/config/auth/');

        foreach ($validUsers as $line) {
            $line = $this->removeLineEndings($line);
            list($validUser, $validPass) = explode(':', $line);

            if ($userName === $validUser) {
                return $this->isPasswordValid($password, $validPass);
            }
        }
    }

    /**
     * @param $password
     * @param $validPass
     * @return bool
     */
    private function isPasswordValid($password, $validPass)
    {
        $encryptedPassword = crypt($password, $this->createSaltForDesFromFirstTwoChars($validPass));

        return $validPass === $encryptedPassword;
    }

    private function createSaltForDesFromFirstTwoChars($validPass)
    {
        return substr($validPass, 0, 2);
    }

    /**
     * @param $line
     * @return mixed
     */
    private function removeLineEndings($line)
    {
        return preg_replace('`[\r\n]$`', '', $line);
    }

    /**
     * @param $fileSystem FileSystem
     * @PdInject fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }
}
