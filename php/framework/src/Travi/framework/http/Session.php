<?php

namespace Travi\framework\http;

class Session
{
    const LOGGED_IN_USER = 'username';

    public function isDebug()
    {
        return false;
    }

    public function logOutUser()
    {
        unset($_SESSION[self::LOGGED_IN_USER]);
    }

    public function setLoggedInUser()
    {
    }
}