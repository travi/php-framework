<?php

namespace travi\framework\view\objects\inputs;

use travi\framework\view\objects\AbstractView;

class Option extends AbstractView
{
    public $text;
    public $value;

    function __construct($text, $value, $selected)
    {
        $this->text     = $text;
        $this->selected = $selected;

        if (isset($value)) {
            $this->value = $value;
        } else {
            $this->value = $text;
        }
    }
}