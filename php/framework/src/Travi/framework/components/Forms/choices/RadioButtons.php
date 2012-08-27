<?php

namespace Travi\framework\components\Forms\choices;

use Travi\framework\components\Forms\choices\Choices;

class RadioButtons extends Choices
{
    public function __construct($options=array())
    {
        parent::__construct($options);
        $this->type = "radio";
        $this->class = "radioButton";
    }
}
 
