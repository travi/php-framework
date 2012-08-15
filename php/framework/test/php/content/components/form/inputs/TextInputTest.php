<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/inputs/TextInput.php';

class TextInputTest extends PHPUnit_Framework_TestCase
{
    /** @var TextInput */
    protected $object;

    protected function setUp()
    {
        $this->object = new TextInput;
    }

    public function testClassName()
    {
        $this->assertSame('textInput', $this->object->getClass());
    }

    public function testType()
    {
        $this->assertSame('text', $this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/inputWithLabel.tpl', $this->object->getTemplate());        
    }
}
