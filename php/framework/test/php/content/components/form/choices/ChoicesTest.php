<?php

use travi\framework\components\Forms\choices\Choices;
use travi\framework\view\objects\inputs\Option;

class ChoicesTest extends PHPUnit_Framework_TestCase
{
    /** @var Choices */
    protected $choices;

    protected function setUp()
    {
        $settings = array('label' => 'label');

        $this->choices = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\choices\\Choices',
            array($settings)
        );
    }

    public function testAddOption()
    {
        $text = 'option';

        $this->choices->addOption($text);

        /** @var Option[] $options */
        $options = $this->choices->getOptions();

        $option = $options[0];

        $this->assertEquals($option->text, $text);
        $this->assertEquals($option->value, $text);
        $this->assertEquals($option->selected, false);
        $this->assertEquals($option->disabled, false);
    }

    public function testGetNameNonePassed()
    {
        $this->assertEquals('label', $this->choices->getName());
    }

    public function testGetNameConstructorSettings()
    {
        $this->choices = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\choices\\Choices',
            array(array('name' => 'name'))
        );

        $this->assertEquals('name', $this->choices->getName());
    }

    public function testGetLabel()
    {
        $this->assertEquals('label', $this->choices->getLabel());
    }

    public function testGetType()
    {
        $this->assertEquals(null, $this->choices->getType());
    }

    public function testGetClass()
    {
        $this->assertEquals(null, $this->choices->getClass());
    }

    public function testValidations()
    {
        $this->choices->addValidation('validation');

        $this->assertEquals(array('validation'), $this->choices->getValidations());
    }

    public function testDefaultTemplate()
    {
        $this->assertEquals('components/form/choices.tpl', $this->choices->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->choices->setTemplate('template');

        $this->assertEquals('template', $this->choices->getTemplate());
    }

    public function testValidIfNoValidationErrors()
    {
        $this->assertTrue($this->choices->isValid());
    }

    public function testNotValidWhenRequiredFieldHasNoValue()
    {
        $this->choices->addValidation('required');
        $this->choices->setValue('');

        $this->assertFalse($this->choices->isValid());
        $this->assertEquals(
            $this->choices->getLabel() . ' is required',
            $this->choices->getValidationError()
        );
    }

    public function testValidWhenRequiredFieldHasValue()
    {
        $this->choices->addValidation('required');
        $this->choices->setValue('something');

        $this->assertTrue($this->choices->isValid());
    }

    public function testAddOptionsCreatesProperOptionList()
    {
        $text1 = 'option 1';
        $value1 = 'some value';
        $text2 = 'option 2';
        $value2 = 'some other value';
        $this->choices->addOptions(
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
        $options = $this->choices->getOptions();

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
        $this->choices->addOptions($options);

        $this->choices->setValue($someValue);

        $returnedValue = $this->choices->getValue();
        $this->assertEquals($someValue, $returnedValue);
        $this->assertSelectedOptionIs($returnedValue, $this->choices->getOptions());
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
        $this->choices->addOptions($options);

        $this->choices->setValue($someValue);

        $returnedValue = $this->choices->getValue();
        $this->assertEquals($someValue, $returnedValue);
        $this->assertSelectedOptionIs($returnedValue, $this->choices->getOptions());
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
