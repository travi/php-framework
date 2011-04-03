<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/src/components/form/Form.php';
require_once '/Users/travi/development/include/php/framework/src/components/form/FieldSet.php';
require_once '/Users/travi/development/include/php/framework/src/components/form/inputs/TextInput.php';
require_once '/Users/travi/development/include/php/framework/src/components/form/inputs/FileInput.php';

/**
 * Test class for Form.
 * Generated by PHPUnit on 2011-01-25 at 18:34:44.
 */
class FormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Form
     */
    protected $form;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $options = array();
        $options['name'] = 'name';
        $options['action'] = 'action';

        $this->form = new Form($options);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testGetName()
    {
        $this->assertSame('name', $this->form->getName());
    }

    public function testMethodDefaultsToPost()
    {
        $this->assertSame('post', $this->form->getMethod());
    }

    public function testMethodOverride()
    {
        $this->form->setMethod('method');
        $this->assertSame('method', $this->form->getMethod());
    }

    public function testGetAction()
    {
        $this->assertSame('action', $this->form->getAction());
    }

    public function testNormalEncType()
    {
        $this->assertSame(null, $this->form->getEncType());
    }

    public function testGetEncTypeWithFileInputPresent()
    {
        $anyField = $this->getMock('FileInput');

        $this->form->addFormElement($anyField);

        $this->assertSame("multipart/form-data", $this->form->getEncType());
    }

    public function testFormAcceptsFieldSets()
    {
        $fieldSet = new FieldSet('fieldset');

        $this->form->addFormElement($fieldSet);

        $this->assertSame(array($fieldSet), $this->form->getFormElements());
    }

    public function testFormAcceptsFields()
    {
        $anyField = $this->getMock('TextInput');

        $this->form->addFormElement($anyField);

        $this->assertSame(array($anyField), $this->form->getFormElements());
    }

    public function testDependencyInitialization()
    {
        $dependencies = $this->form->getDependencies();
        $this->assertContains('formAlign', $dependencies['scripts']);
        $this->assertContains('/resources/shared/css/travi.form.css', $dependencies['styles']);
        $this->assertContains("$('form[name=\"name\"]').alignFields();", $dependencies['jsInits']);
    }

    public function testValidationScriptAddedToDependencyList()
    {
        $validations = $this->getAnyValidations();
        $anyField = $this->getAnyFieldWithValidations($validations);

        $this->form->addFormElement($anyField);

        $dependencies = $this->form->getDependencies();
        $this->assertContains('validation', $dependencies['scripts']);

    }

    public function testValidationsArePassedInGetDependencies()
    {
        $validations = $this->getAnyValidations();
        $anyField = $this->getAnyFieldWithValidations($validations);

        $this->form->addFormElement($anyField);

        $dependencies = $this->form->getDependencies(); 
        $this->assertSame($validations, $dependencies['validations']);
    }

    private function getAnyValidations()
    {
        return array('required');
    }

    private function getAnyField()
    {
        return $this->getMock('TextInput');
    }

    private function getAnyFieldWithValidations($validations = array())
    {
        $field = $this->getAnyField();
        if (empty($validations)) {
            $validations = $this->getAnyValidations();
        }

        $field->expects($this->any())
                ->method('getValidations')
                ->will($this->returnValue($validations));

        return $field;
    }
}
?>
