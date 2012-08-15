<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/inputs/TextArea.php';

class TextAreaTest extends PHPUnit_Framework_TestCase
{
    /** @var TextArea */
    protected $textArea;

    protected function setUp()
    {
        $options = array();
        $options['rows'] = 4;

        $this->textArea = new TextArea($options);
    }

    public function testGetRows()
    {
        $this->assertSame(4, $this->textArea->getRows());
    }

    public function testGetRowsDefault()
    {
        $textArea = new TextArea(array());

        $this->assertEquals(null, $textArea->getRows());
    }

    public function testClassName()
    {
        $this->assertSame('textInput', $this->textArea->getClass());
    }

    public function testType()
    {
        $this->assertNull($this->textArea->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/textArea.tpl', $this->textArea->getTemplate());
    }
}
?>
