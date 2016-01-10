<?php

require_once 'ChoicesTest.php';

use travi\framework\components\Forms\choices\SelectionBox;
use travi\framework\view\objects\inputs\Option;

class SelectionBoxTest extends ChoicesTest
{
    /** @var SelectionBox */
    protected $field;

    protected function setUp()
    {
        $this->field = new SelectionBox($this->settings);
    }

    public function testAddOption()
    {
        $text = 'option';

        $this->field->addOption($text);

        /** @var Option[] $options */
        $options = $this->field->getOptions();

        $firstOption = $options[0];

        $this->assertEquals('Select One', $firstOption->text);
        $this->assertEquals('', $firstOption->value);

        $secondOption = $options[1];

        $this->assertEquals($text, $secondOption->text);
        $this->assertEquals($text, $secondOption->value);
    }

    public function testDefaultTemplate()
    {
        $this->assertEquals('components/form/selectionBox.tpl', $this->field->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->field->setTemplate('template');

        $this->assertEquals('template', $this->field->getTemplate());
    }
}
