<?php

require_once __DIR__ . '/../FieldTest.php';

use travi\framework\components\Forms\inputs\EmailInput;

class EmailInputTest extends FieldTest
{
    const ERROR_MESSAGE = 'Please enter a valid email address.';
    /** @var  EmailInput */
    protected $field;

    public function setUp()
    {
        $this->field = new EmailInput();
    }

    public function testThatAttributesAreSetCorrectly()
    {
        $this->assertEquals('email', $this->field->getType());
        $this->assertEquals('textInput', $this->field->getClass());
    }

    public function testThatInitializingWithOptionsSetsOptions()
    {
        $label = 'Some Label';
        $validations = array('required');

        $field = new EmailInput(
            array(
                'label' => $label,
                'validations' => $validations
            )
        );

        $this->assertEquals($label, $field->getLabel());
        $this->assertEquals('some_label', $field->getName());
        $this->assertEquals($validations, $field->getValidations());
        $this->assertEquals('components/form/inputWithLabel.tpl', $field->getTemplate());
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
            self::ERROR_MESSAGE,
            $this->field->getValidationError()
        );
    }

    public function testThatValueMustContainAPeriodAfterTheAtSymbol()
    {
        $this->field->setValue('sadf@asdf');

        $this->assertFalse($this->field->isValid());
        $this->assertEquals(
            self::ERROR_MESSAGE,
            $this->field->getValidationError()
        );
    }

}