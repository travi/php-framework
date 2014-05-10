<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\inputs\TextArea;

class RichTextArea extends TextArea
{
    public function __construct($options)
    {
        parent::__construct($options);
        $this->setClass("textInput richEditor");
        $this->addJavaScript('wymEditor');
        $this->addJavaScript('wymEditor-fullScreen');
        $this->addJavaScript('travi');
        $this->addJavaScript('/resources/thirdparty/travi-ui/js/form/richText.js');
        $this->setTemplate('components/form/richTextArea.tpl');
    }
}