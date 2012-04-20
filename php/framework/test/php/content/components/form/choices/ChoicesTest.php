<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/choices/Choices.php';

class ChoicesTest extends PHPUnit_Framework_TestCase
{
    /** @var Choices */
    protected $choices;

    protected function setUp()
    {
        $settings = array('label' => 'label');

        $this->choices = $this->getMockForAbstractClass('Choices', array($settings));
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
        $this->choices = $this->getMockForAbstractClass('Choices', array(array('name' => 'name')));

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
}
