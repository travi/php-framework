<?php

namespace Travi\framework\components\Forms\inputs;

use Travi\framework\components\Forms\inputs\Input;

class HiddenInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setType("hidden");
        $this->setTemplate('components/form/input.tpl');
    }
}
 
