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
        $this->addJsInit(
            "$('textarea.richEditor').wymeditor({
                    skin: 'silver',
                    updateSelector: 'form',
                    postInit: function (wym) {
                        wym.fullscreen();
                    }
                });"
        );
        $this->setTemplate('components/form/richTextArea.tpl');
    }
}