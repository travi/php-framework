<?php

use Travi\framework\components\Forms\inputs\NumberInput;

class NumberInputTest extends PHPUnit_Framework_TestCase
{
    public function testNumberInputConfiguredCorrectly()
    {
        $numberInput = new NumberInput();

        $this->assertSame('textInput', $numberInput->getClass());
        $this->assertSame('number', $numberInput->getType());
        $this->assertSame('components/form/inputWithLabel.tpl', $numberInput->getTemplate());
    }
}