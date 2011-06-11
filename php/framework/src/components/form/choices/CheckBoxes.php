<?php

require_once 'Choices.php';

class CheckBoxes extends Choices
{
    public function __construct($options=array())
    {
        parent::__construct($options);
        $this->type = "checkbox";
        $this->class = "checkbox";
    }
}
 
