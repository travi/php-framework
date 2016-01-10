<?php

require_once __DIR__ . '/../FieldTest.php';

use travi\framework\components\Forms\inputs\TextArea;

class TextAreaTest extends FieldTest
{
    /** @var TextArea */
    protected $field;

    protected function setUp()
    {
        $options = array();
        $options['rows'] = 4;

        $this->field = new TextArea($options);
    }

    public function testGetRows()
    {
        $this->assertSame(4, $this->field->getRows());
    }

    public function testGetRowsDefault()
    {
        $textArea = new TextArea(array());

        $this->assertEquals(null, $textArea->getRows());
    }

    public function testClassName()
    {
        $this->assertSame('textInput', $this->field->getClass());
    }

    public function testType()
    {
        $this->assertNull($this->field->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/textArea.tpl', $this->field->getTemplate());
    }
}
?>
