<?php

use Travi\framework\components\Forms\choices\SelectionBox;

class SelectionBoxTest extends PHPUnit_Framework_TestCase
{
    /** @var SelectionBox */
    protected $object;

    protected function setUp()
    {
        $this->object = new SelectionBox;
    }

    public function testAddOption()
    {
        $this->object->addOption('option');

        $this->assertSame(
            array(
                 array(
                     'option' => 'Select One',
                     'value' => '',
                     'selected' => false,
                     'disabled' => false
                 ),
                 array(
                     'option' => 'option',
                     'value' => '',
                     'selected' => false,
                     'disabled' => false
                 )
            ),
            $this->object->getOptions()
        );
    }

    public function testDefaultTemplate()
    {
        $this->assertSame('components/form/selectionBox.tpl', $this->object->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->object->setTemplate('template');

        $this->assertSame('template', $this->object->getTemplate());
    }
}
?>
