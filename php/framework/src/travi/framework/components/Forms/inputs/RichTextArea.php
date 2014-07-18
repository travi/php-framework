<?php

namespace travi\framework\components\Forms\inputs;

class RichTextArea extends TextArea
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setClass("textInput richEditor");
        $this->addJavaScript('richTextArea');
        $this->setTemplate('components/form/richTextArea.tpl');
    }
}