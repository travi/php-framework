<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/inputs/Input.php';

/**
 * Test class for Input.
 * Generated by PHPUnit on 2011-01-25 at 21:26:06.
 */
class InputTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Input
     */
    protected $input;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $options = array();
        $options['label'] = 'label';
        $options['value'] = 'value';
        $options['validations'] = array('validation1', 'validation2');

        $this->input = $this->getMockForAbstractClass('Input', array($options));
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @todo Implement testAddValidation().
     */
    public function testAddValidation()
    {
        $this->input->addValidation('validation3');

        $this->assertSame(array('validation1', 'validation2', 'validation3'), $this->input->getValidations());
    }

    public function testConstructorSetNameWhenNameIncluded()
    {
        $options = array();
        $options['name'] = 'input_name';
        $options['label'] = 'label';
        $options['value'] = 'value';
        $options['validations'] = array('validation1', 'validation2');

        /** @var $input Input */
        $input = $this->getMockForAbstractClass('Input', array($options));

        $this->assertSame('input_name', $input->getName());
    }

    public function testConstructorSetNameToLabelWhenNameNotIncluded()
    {
        $this->assertSame('label', $this->input->getName());
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
        $this->assertSame('label', $this->input->getLabel());
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
}
?>
