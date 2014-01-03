<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\inputs\Input;

class FileInput extends Input
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("fileInput");
        $this->setType("file");
    }
}