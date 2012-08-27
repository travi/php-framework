<?php

use Travi\framework\components\Forms\choices\RadioButtons;

class RadioButtonsTest extends PHPUnit_Framework_TestCase
{
    /** @var RadioButtons */
    protected $object;

    protected function setUp()
    {
        $this->object = new RadioButtons;
    }

    public function testDefaults()
    {
        $this->assertSame('components/form/choices.tpl', $this->object->getTemplate());
        $this->assertSame('radio', $this->object->getType());
        $this->assertSame('radioButton', $this->object->getClass());
    }
}
