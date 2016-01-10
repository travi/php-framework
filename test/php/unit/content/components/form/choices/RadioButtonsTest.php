<?php

require_once 'ChoicesTest.php';

use travi\framework\components\Forms\choices\RadioButtons;
use travi\framework\view\objects\inputs\Option;

class RadioButtonsTest extends ChoicesTest
{
    /** @var RadioButtons */
    protected $field;

    protected function setUp()
    {
        $this->field = new RadioButtons($this->settings);
    }

    public function testDefaults()
    {
        $this->assertSame('components/form/choices.tpl', $this->field->getTemplate());
        $this->assertSame('radio', $this->field->getType());
        $this->assertSame('radioButton', $this->field->getClass());
    }

    public function testThatRadioClassIsAdded()
    {
        $this->assertEquals('radioButton', $this->field->getClass());
    }

    public function testThatTypeIsSetToRadio()
    {
        $this->assertEquals('radio', $this->field->getType());
    }
}
