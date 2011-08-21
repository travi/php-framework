<?php
require_once 'TextArea.php';

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