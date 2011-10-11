<?php
require_once 'Input.php';

class TextArea extends Input
{
    private $rows;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("textInput");
        $this->rows = $options['rows'];
        $this->setTemplate('components/form/textArea.tpl');
    }
    
    public function getRows()
    {
        return $this->rows;
    }
}