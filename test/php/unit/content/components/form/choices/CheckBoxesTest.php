<?php

require_once 'ChoicesTest.php';

use travi\framework\components\Forms\choices\CheckBoxes;

class CheckBoxesTest extends ChoicesTest
{
    /** @var CheckBoxes */
    protected $field;

    protected function setUp()
    {
        $this->field = new CheckBoxes($this->settings);
    }

    public function testDefaults()
    {
        $this->assertSame('components/form/choices.tpl', $this->field->getTemplate());
        $this->assertSame('checkbox', $this->field->getType());
        $this->assertSame('checkbox', $this->field->getClass());
    }


    public function testThatCheckboxClassIsAdded()
    {
        $this->assertEquals('checkbox', $this->field->getClass());
    }

    public function testThatTypeIsSetToCheckbox()
    {
        $this->assertEquals('checkbox', $this->field->getType());
    }
}
