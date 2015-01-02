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

        $this->assertFalse($this->field->isValid());
        $this->assertEquals(
            $this->field->getLabel() . ' is required',
            $this->field->getValidationError()
        );
    }
}