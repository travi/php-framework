<?php

require_once __DIR__ . '/../FieldTest.php';

use travi\framework\components\Forms\choices\Choices;
use travi\framework\view\objects\inputs\Option;

class ChoicesTest extends FieldTest
{
    /** @var Choices */
    protected $field;

    protected function setUp()
    {
        $settings = array('label' => 'label');

        $this->field = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\choices\\Choices',
            array($settings)
        );
    }

    public function testAddOption()
    {
        $text = 'option';

        $this->field->addOption($text);

        /** @var Option[] $options */
        $options = $this->field->getOptions();

        $option = $options[0];

        $this->assertEquals($option->text, $text);
        $this->assertEquals($option->value, $text);
        $this->assertEquals($option->selected, false);
    }

    public function testGetNameNonePassed()
    {
        $this->assertEquals('label', $this->field->getName());
    }

    public function testGetNameConstructorSettings()
    {
        $this->field = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\choices\\Choices',
            array(array('name' => 'name'))
        );

        $this->assertEquals('name', $this->field->getName());
    }

    public function testGetLabel()
    {
        $this->assertEquals('label', $this->field->getLabel());
    }

    public function testGetType()
    {
        $this->assertEquals(null, $this->field->getType());
    }

    public function testGetClass()
    {
        $this->assertEquals(null, $this->field->getClass());
    }

    public function testValidations()
    {
        $this->field->addValidation('validation');

        $this->assertEquals(array('validation'), $this->field->getValidations());
    }

    public function testDefaultTemplate()
    {
        $this->assertEquals('components/form/choices.tpl', $this->field->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->field->setTemplate('template');

        $this->assertEquals('template', $this->field->getTemplate());
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

    public function testAddOptionsCreatesProperOptionList()
    {
        $text1 = 'option 1';
        $value1 = 'some value';
        $text2 = 'option 2';
        $value2 = 'some other value';
        $this->field->addOptions(
            array(
                array(
                    'label' => $text1,
                    'value' => $value1
                ),
                array(
                    'label' => $text2,
                    'value' => $value2
                )
            )
        );

        /** @var Option[] $options */
        $options = $this->field->getOptions();

        $firstOption = $options[0];

        $this->assertEquals($text1, $firstOption->text);
        $this->assertEquals($value1, $firstOption->value);

        $secondOption = $options[1];

        $this->assertEquals($text2, $secondOption->text);
        $this->assertEquals($value2, $secondOption->value);
    }

    public function testSetValueMarksSimpleOptionAsSelectedInList()
    {
        $someValue = 'option 1';
        $options = array(
            $someValue,
            'option 2'
        );
        $this->field->addOptions($options);

        $this->field->setValue($someValue);

        $returnedValue = $this->field->getValue();
        $this->assertEquals($someValue, $returnedValue);
        $this->assertSelectedOptionIs($returnedValue, $this->field->getOptions());
    }

    public function testSetValueMarksComplexOptionAsSelectedInList()
    {
        $someValue = 'something';
        $options = array(
            array(
                'label' => 'option 1',
                'value' => $someValue
            ),
            array(
                'label' => 'option 2',
                'value' => 'some other value'
            )
        );
        $this->field->addOptions($options);

        $this->field->setValue($someValue);

        $returnedValue = $this->field->getValue();
        $this->assertEquals($someValue, $returnedValue);
        $this->assertSelectedOptionIs($returnedValue, $this->field->getOptions());
    }

    private function assertSelectedOptionIs($value, $options)
    {
        $selected = 'provided selection (' . $value . ') is not selected';

        /** @var Option $option */
        foreach ($options as $option) {
            if ($option->selected === true) {
                $selected = $option->value;
            }
        }

        $this->assertEquals($value, $selected);
    }
}
