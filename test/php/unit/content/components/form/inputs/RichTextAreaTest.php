<?php

require_once __DIR__ . '/../FieldTest.php';

use travi\framework\components\Forms\inputs\RichTextArea;

class RichTextAreaTest extends FieldTest
{
    /** @var RichTextArea */
    protected $field;

    protected function setUp()
    {
        $this->field = new RichTextArea;
    }

    public function testClassName()
    {
        $this->assertSame('textInput richEditor', $this->field->getClass());
    }

    public function testType()
    {
        $this->assertNull($this->field->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/richTextArea.tpl', $this->field->getTemplate());
    }

    public function testGetJavaScripts()
    {
        $this->assertSame(
            array(
                'richTextArea'
            ),
            $this->field->getJavaScripts()
        );
    }
}
