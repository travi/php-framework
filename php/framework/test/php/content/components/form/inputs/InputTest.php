<?php

require_once __DIR__ . '/../FieldTest.php';

use travi\framework\components\Forms\inputs\Input;

class InputTest extends FieldTest
{
    const LABEL_KEY = self::ANY_LABEL;
    const ANY_LABEL = 'label';
    /**
     * @var Input
     */
    protected $field;

    protected function setUp()
    {
        $options = array();
        $options[self::ANY_LABEL] = self::ANY_LABEL;
        $options['value'] = 'value';
        $options['validations'] = array('validation1', 'validation2');

        $this->field = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\inputs\\Input',
            array($options)
        );
    }

    public function testAddValidation()
    {
        $this->field->addValidation('validation3');

        $this->assertSame(array('validation1', 'validation2', 'validation3'), $this->field->getValidations());
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
        $this->assertSame(self::ANY_LABEL, $this->field->getName());
    }

    public function testSetNameLowerCased()
    {
        $nameWithCapitals = 'NameWithCapitals';

        $this->field->setName($nameWithCapitals);

        $this->assertSame(strtolower($nameWithCapitals), $this->field->getName());
    }

    public function testSetNameSpacesToUnderscores()
    {
        $nameWithSpaces = 'name with spaces';

        $this->field->setName($nameWithSpaces);

        $this->assertSame(str_replace(' ', '_', $nameWithSpaces), $this->field->getName());
    }

    public function testSetNameWithNameExpando()
    {
        $nameExpando = 'name';

        $this->field->setName($nameExpando);

        $this->assertSame($nameExpando . '_value', $this->field->getName());
    }

    public function testSetNameWithIdExpando()
    {
        $idExpando = 'id';

        $this->field->setName($idExpando);

        $this->assertSame($idExpando . '_value', $this->field->getName());
    }

    public function testGetLabel()
    {
        $this->assertSame(self::ANY_LABEL, $this->field->getLabel());
    }

    public function testSetLabel()
    {
        $someLabel = 'some other label';

        $this->field->setLabel($someLabel);

        $this->assertSame($someLabel, $this->field->getLabel());
    }

    public function testGetType()
    {
        $this->assertSame(null, $this->field->getType());
    }

    public function testGetValue()
    {
        $this->assertSame('value', $this->field->getValue());
    }

    public function testGetClass()
    {
        $this->assertSame(null, $this->field->getClass());
    }

    public function testGetValidations()
    {
        $this->assertSame(array('validation1', 'validation2'), $this->field->getValidations());
    }

    public function testValidationError()
    {
        $message = 'some message';
        $this->field->setValidationError($message);

        $this->assertEquals($message, $this->field->getValidationError());
    }

    public function testValidIfNoValidationErrors()
    {
        $this->assertTrue($this->field->isValid());
    }

    public function testValidWhenRequiredFieldHasValue()
    {
        $this->field->addValidation('required');
        $this->field->setValue('something');

        $this->assertTrue($this->field->isValid());
    }
}
