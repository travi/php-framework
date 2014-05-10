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
        $this->assertSame(
            array(
                'wymEditor',
                'wymEditor-fullScreen',
                '/resources/thirdparty/travi-ui/js/form/richText.js'
            ),
            $this->object->getJavaScripts()
        );
    }
}
