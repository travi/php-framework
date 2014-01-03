<?php

use travi\framework\components\Forms\inputs\Input;

class InputTest extends PHPUnit_Framework_TestCase
{
    const LABEL_KEY = self::ANY_LABEL;
    const ANY_LABEL = 'label';
    /**
     * @var Input
     */
    protected $input;

    protected function setUp()
    {
        $options = array();
        $options[self::ANY_LABEL] = self::ANY_LABEL;
        $options['value'] = 'value';
        $options['validations'] = array('validation1', 'validation2');

        $this->input = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\inputs\\Input',
            array($options)
        );
    }

    public function testAddValidation()
    {
        $this->input->addValidation('validation3');

        $this->assertSame(array('validation1', 'validation2', 'validation3'), $this->input->getValidations());
    }

    public function testConstructorSetNameWhenNameIncluded()
    {
        $options = array();
        $options['name'] = 'input_name';
        $options[self::LABEL_KEY] = self::ANY_LABEL;
        $options['value'] = 'value';
        $options['validations'] = array('validation1', 'validation2');

        /** @var $input Input */
        $input = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\inputs\\Input',
            array($options)
        );

        $this->assertSame('input_name', $input->getName());
    }

    public function testConstructorSetNameToLabelWhenNameNotIncluded()
    {
        $this->assertSame(self::ANY_LABEL, $this->input->getName());
    }

    public function testSetNameLowerCased()
    {
        $nameWithCapitals = 'NameWithCapitals';

        $this->input->setName($nameWithCapitals);

        $this->assertSame(strtolower($nameWithCapitals), $this->input->getName());
    }

    public function testSetNameSpacesToUnderscores()
    {
        $nameWithSpaces = 'name with spaces';

        $this->input->setName($nameWithSpaces);

        $this->assertSame(str_replace(' ', '_', $nameWithSpaces), $this->input->getName());
    }

    public function testSetNameWithNameExpando()
    {
        $nameExpando = 'name';

        $this->input->setName($nameExpando);

        $this->assertSame($nameExpando . '_value', $this->input->getName());
    }

    public function testSetNameWithIdExpando()
    {
        $idExpando = 'id';

        $this->input->setName($idExpando);

        $this->assertSame($idExpando . '_value', $this->input->getName());
    }

    public function testGetLabel()
    {
        $this->assertSame(self::ANY_LABEL, $this->input->getLabel());
    }

    public function testSetLabel()
    {
        $someLabel = 'some other label';

        $this->input->setLabel($someLabel);

        $this->assertSame($someLabel, $this->input->getLabel());
    }

    public function testGetType()
    {
        $this->assertSame(null, $this->input->getType());
    }

    public function testGetValue()
    {
        $this->assertSame('value', $this->input->getValue());
    }

    public function testGetClass()
    {
        $this->assertSame(null, $this->input->getClass());
    }

    public function testGetValidations()
    {
        $this->assertSame(array('validation1', 'validation2'), $this->input->getValidations());
    }

    public function testValidationError()
    {
        $message = 'some message';
        $this->input->setValidationError($message);

        $this->assertEquals($message, $this->input->getValidationError());
    }

    public function testValidIfNoValidationErrors()
    {
        $this->assertTrue($this->input->isValid());
    }

    public function testNotValidWhenRequiredFieldHasNoValue()
    {
        $this->input->addValidation('required');
        $this->input->setValue('');

        $this->assertFalse($this->input->isValid());
        $this->assertEquals(
            $this->input->getLabel() . ' is required',
            $this->input->getValidationError()
        );
    }

    public function testValidWhenRequiredFieldHasValue()
    {
        $this->input->addValidation('required');
        $this->input->setValue('something');

        $this->assertTrue($this->input->isValid());
    }
}
