<?php

namespace travi\framework\components\Forms\inputs;

class FileInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setClass("fileInput");
        $this->setType("file");
    }
}