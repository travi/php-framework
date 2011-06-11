<?php
/**
 * Created on Jan 26, 2011
 * By Matt Travi
 * programmer@travi.org
 */

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
                updateSelector: '#Submit',
                postInit: function (wym) {
                    wym.fullscreen();
                }
            });"
        );
        $this->setTemplate('components/form/richTextArea.tpl');
    }
}