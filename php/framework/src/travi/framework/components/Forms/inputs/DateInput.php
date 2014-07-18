<?php

namespace travi\framework\components\Forms\inputs;

class DateInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setType("date");
        $this->setClass("textInput datepicker");
        $this->addJavaScript('datePicker');
    }
}
