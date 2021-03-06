<?php

use travi\framework\components\Forms\inputs\HiddenInput;

class HiddenInputTest extends PHPUnit_Framework_TestCase
{
    /** @var HiddenInput */
    protected $object;

    protected function setUp()
    {
        $this->object = new HiddenInput;
    }

    public function testClassName()
    {
        $this->assertNull($this->object->getClass());
    }

    public function testType()
    {
        $this->assertSame('hidden', $this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/input.tpl', $this->object->getTemplate());
    }
}
?>
