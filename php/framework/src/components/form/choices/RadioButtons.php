<?php

require_once('Choices.php');

class RadioButtons extends Choices
{
    public function __construct($options=array())
    {
        parent::__construct($options);
        $this->type = "radio";
        $this->class = "radioButton";
    }
}
 
