<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\inputs\Input;

class HiddenInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setType("hidden");
        $this->setTemplate('components/form/input.tpl');
    }
}
 
