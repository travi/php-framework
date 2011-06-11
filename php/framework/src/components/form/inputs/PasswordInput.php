<?php

require_once 'Input.php';

class PasswordInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("textInput");
        $this->setType("password");
    }
}