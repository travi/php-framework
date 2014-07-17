<?php

namespace travi\framework\components\Forms\inputs;

class TextArea extends Input
{
    private $rows;

    public function __construct($options)
    {
        parent::__construct($options);

        $this->setClass("textInput");
        $this->setTemplate('components/form/textArea.tpl');

        if (isset($options['rows'])) {
            $this->rows = $options['rows'];
        }
    }
    
    public function getRows()
    {
        return $this->rows;
    }
}