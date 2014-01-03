<?php

use travi\framework\components\Forms\inputs\RichTextArea;

class RichTextAreaTest extends PHPUnit_Framework_TestCase
{
    /** @var RichTextArea */
    protected $object;

    protected function setUp()
    {
        $this->object = new RichTextArea;
    }

    public function testClassName()
    {
        $this->assertSame('textInput richEditor', $this->object->getClass());
    }

    public function testType()
    {
        $this->assertNull($this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/richTextArea.tpl', $this->object->getTemplate());
    }

    public function testGetJavaScripts()
    {
        $this->assertSame(array('wymEditor','wymEditor-fullScreen'), $this->object->getJavaScripts());
    }

    public function testGetJsInits()
    {
        $this->assertSame(
            array(
                 "$('textarea.richEditor').wymeditor({
                    skin: 'silver',
                    updateSelector: 'form',
                    postInit: function (wym) {
                        wym.fullscreen();
                    }
                });"
            ),
            $this->object->getJsInits()
        );
    }
}
