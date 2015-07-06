<?php

namespace travi\framework\components\Forms\choices;

class RadioButtons extends Choices
{
    public function __construct($options=array())
    {
        parent::__construct($options);

        $this->type  = "radio";
        $this->class = "radioButton";
    }
}
