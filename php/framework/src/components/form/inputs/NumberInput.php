<?php

require_once 'Input.php';

class NumberInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("textInput");
        $this->setType("number");
    }
}