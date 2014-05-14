<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\inputs\TextArea;

class RichTextArea extends TextArea
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("textInput richEditor");
        $this->addJavaScript('richTextArea');
        $this->setTemplate('components/form/richTextArea.tpl');
    }
}