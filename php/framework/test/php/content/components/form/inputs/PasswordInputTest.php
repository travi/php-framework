<?php
use Travi\framework\components\Forms\inputs\PasswordInput;

class PasswordInputTest extends PHPUnit_Framework_TestCase
{
    /** @var PasswordInput */
    protected $object;

    protected function setUp()
    {
        $this->object = new PasswordInput;
    }

    public function testClassName()
    {
        $this->assertSame('textInput', $this->object->getClass());
    }

    public function testType()
    {
        $this->assertSame('password', $this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/inputWithLabel.tpl', $this->object->getTemplate());
    }
}
?>
