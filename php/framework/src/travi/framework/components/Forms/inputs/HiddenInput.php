<?php

namespace travi\framework\components\Forms\inputs;

class HiddenInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setType("hidden");
        $this->setTemplate('components/form/input.tpl');
    }
}
 
