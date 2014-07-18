<?php

namespace travi\framework\components\Forms\inputs;

class PasswordInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setClass("textInput");
        $this->setType("password");
    }
}