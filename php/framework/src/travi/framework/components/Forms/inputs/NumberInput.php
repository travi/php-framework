<?php
namespace travi\framework\components\Forms\inputs;

class NumberInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setClass("textInput");
        $this->setType("number");
    }
}