<?php

namespace travi\framework\http;

class Session
{
    const LOGGED_IN_USER = 'username';

    public function __construct()
    {
        session_start();
    }

    public function isDebug()
    {
        return false;
    }

    public function logOutUser()
    {
        $_SESSION = array();
        session_destroy();
    }

    public function setLoggedInUser()
    {
    }
}
