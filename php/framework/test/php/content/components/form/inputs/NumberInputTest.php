<?php

require_once dirname(__FILE__).'/../../../../../../src/components/form/inputs/NumberInput.php';

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