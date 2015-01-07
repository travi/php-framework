<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\Field;

abstract class Input extends Field
{
    public function __construct($options = array())
    {
        parent::__construct($options);

        $this->setTemplate('components/form/inputWithLabel.tpl');
    }

    public function setName($name)
    {
        $name = str_replace(' ', '_', strtolower($name));

        //ensure value is not "name" or "id" (expandos)
        if ($name === 'name' || $name === 'id') {
            $name .= '_value';
        }

        $this->name = $name;
    }
}