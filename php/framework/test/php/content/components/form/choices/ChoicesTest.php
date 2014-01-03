<?php

use travi\framework\components\Forms\choices\Choices;

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
        $this->choices->addOption('option');

        $this->assertSame(
            array(
                 array(
                     'option' => 'option',
                     'value' => '',
                     'selected' => false,
                     'disabled' => false
                 )
            ),
            $this->choices->getOptions()
        );
    }

    public function testGetNameNonePassed()
    {
        $this->assertSame('label', $this->choices->getName());
    }

    public function testGetNameConstructorSettings()
    {
        $this->choices = $this->getMockForAbstractClass(
            'travi\\framework\\components\\Forms\\choices\\Choices',
            array(array('name' => 'name'))
        );

        $this->assertSame('name', $this->choices->getName());
    }

    public function testGetLabel()
    {
        $this->assertSame('label', $this->choices->getLabel());
    }

    public function testGetType()
    {
        $this->assertSame(null, $this->choices->getType());
    }

    public function testGetClass()
    {
        $this->assertSame(null, $this->choices->getClass());
    }

    public function testValidations()
    {
        $this->choices->addValidation('validation');

        $this->assertSame(array('validation'), $this->choices->getValidations());
    }

    public function testDefaultTemplate()
    {
        $this->assertSame('components/form/choices.tpl', $this->choices->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->choices->setTemplate('template');

        $this->assertSame('template', $this->choices->getTemplate());
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

        $this->assertEquals(
            array(
                array(
                    'option' => $text1,
                    'value' => $value1,
                    'selected' => '',
                    'disabled' => ''
                ),
                array(
                    'option' => $text2,
                    'value' => $value2,
                    'selected' => '',
                    'disabled' => ''
                )
            ),
            $this->choices->getOptions()
        );
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
        foreach ($options as $option) {
            if ($option['selected'] === true) {
                if (empty($option['value'])) {
                    $selected = $option['option'];
                } else {
                    $selected = $option['value'];
                }
            }
        }
        $this->assertEquals($value, $selected);
    }
}
