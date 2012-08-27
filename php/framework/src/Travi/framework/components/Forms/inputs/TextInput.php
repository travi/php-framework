<?php

namespace Travi\framework\components\Forms\inputs;

use Travi\framework\components\Forms\inputs\Input;

class TextInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("textInput");
        $this->setType("text");
    }
}