<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/src/components/form/Choices.php';

/**
 * Test class for Choices.
 * Generated by PHPUnit on 2011-02-13 at 12:55:43.
 */
class ChoicesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Choices
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $settings = array('label' => 'label');

        $this->object = $this->getMockForAbstractClass('Choices', array($settings));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testAddOption()
    {
        $this->object->addOption('option');

        $this->assertSame(array(array('option' => 'option', 'value' => '',
                'selected' => false, 'disabled' => false)),
            $this->object->getOptions());
    }

    public function testGetNameNonePassed()
    {
        $this->assertSame('label', $this->object->getName());
    }

    public function testGetNameConstructorSettings()
    {
        $this->object = $this->getMockForAbstractClass('Choices', array(array('name' => 'name')));

        $this->assertSame('name', $this->object->getName());
    }

    public function testGetLabel()
    {
        $this->assertSame('label', $this->object->getLabel());
    }

    public function testGetType()
    {
        $this->assertSame(null, $this->object->getType());
    }

    public function testGetClass()
    {
        $this->assertSame(null, $this->object->getClass());
    }

    public function testValidations()
    {
        $this->object->addValidation('validation');

        $this->assertSame(array('validation'), $this->object->getValidations());
    }

    public function testDefaultTemplate()
    {
        $this->assertSame('components/form/choices.tpl', $this->object->getTemplate());
    }

    public function testSetTemplate()
    {
        $this->object->setTemplate('template');

        $this->assertSame('template', $this->object->getTemplate());
    }
}
?>
