<?php

use travi\framework\components\Forms\inputs\EmailInput;

class EmailInputTest extends FieldTest
{
    /** @var  EmailInput */
    protected $field;

    public function setUp()
    {
        $this->field = new EmailInput();
    }

    public function testThatValidEmailPassesValidation()
    {
        $this->field->setValue('me@test.org');

        $this->assertTrue($this->field->isValid());
    }

    public function testThatValueMustContainAtSymbol()
    {
        $this->field->setValue('sadfasdf');

        $this->assertFalse($this->field->isValid());
        $this->assertEquals(
            'A valid email address must be supplied',
            $this->field->getValidationError()
        );
    }

    public function testThatValueMustContainAPeriodAfterTheAtSymbol()
    {
        $this->field->setValue('sadf@asdf');

        $this->assertFalse($this->field->isValid());
        $this->assertEquals(
            'A valid email address must be supplied',
            $this->field->getValidationError()
        );
    }

}