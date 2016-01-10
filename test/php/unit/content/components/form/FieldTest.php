<?php

use travi\framework\components\Forms\Field;

abstract class FieldTest extends PHPUnit_Framework_TestCase
{
    /** @var  Field */
    protected $field;

    public function testNotValidWhenRequiredFieldHasNoValue()
    {
        $this->field->addValidation('required');
        $this->field->setValue('');

        $this->assertInvalid();
    }

    public function testNotValidWhenRequiredFieldContainsOnly()
    {
        $this->field->addValidation('required');
        $this->field->setValue("\n");

        $this->assertInvalid();
    }

    public function testThatBlankInputIsAcceptableIfNotRequired()
    {
        $this->field->setValue('');

        $this->assertTrue($this->field->isValid());
    }

    public function testThatIdIsSetToEqualName()
    {
        $label = 'Some Name';
        $name = 'some_name';

        $this->field->setName($label);

        $this->assertEquals($name, $this->field->getName());
        $this->assertEquals($name, $this->field->getId());
    }

    private function assertInvalid()
    {
        $this->assertFalse($this->field->isValid());
        $this->assertEquals(
            $this->field->getLabel() . ' is required',
            $this->field->getValidationError()
        );
    }
}