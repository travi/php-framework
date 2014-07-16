<?php

use travi\framework\components\Forms\choices\SelectionBox;
use travi\framework\view\objects\inputs\Option;

class SelectionBoxTest extends PHPUnit_Framework_TestCase
{
    /** @var SelectionBox */
    protected $selectionBox;

    protected function setUp()
    {
        $this->selectionBox = new SelectionBox;
    }

    public function testAddOption()
    {
        $text = 'option';

        $this->selectionBox->addOption($text);

        /** @var Option[] $options */
        $options = $this->selectionBox->getOptions();

        $firstOption = $options[0];

        $this->assertEquals('Select One', $firstOption->text);
        $this->assertEquals('', $firstOption->value);

        $secondOption = $options[1];

        $this->assertEquals($text, $secondOption->text);
        $this->assertEquals($text, $secondOption->value);
    }

    public function testDefaultTemplate()
    {
        $this->assertEquals('components/form/selectionBox.tpl', $this->selectionBox->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->selectionBox->setTemplate('template');

        $this->assertEquals('template', $this->selectionBox->getTemplate());
    }
}
?>
