<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../../../src/components/form/Form.php';
require_once dirname(__FILE__).'/../../../../../src/components/form/FieldSet.php';
require_once dirname(__FILE__).'/../../../../../src/components/form/inputs/TextInput.php';
require_once dirname(__FILE__).'/../../../../../src/components/form/inputs/FileInput.php';

class FormTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Form
     */
    protected $form;

    protected function setUp()
    {
        $options = array();
        $options['name'] = 'name';
        $options['action'] = 'action';

        $this->form = new Form($options);
    }

    public function testGetName()
    {
        $this->assertSame('name', $this->form->getName());
    }

    public function testMethodFromOptions()
    {
        $method = 'some method';
        $form = new Form(array('method' => $method));
        $this->assertEquals($method, $form->getMethod());
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

    public function testSetAction()
    {
        $action = 'someAction';

        $this->form->setAction($action);

        $this->assertSame($action, $this->form->getAction());
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
        /** @var $anyField TextInput */
        $anyField = $this->getAnyFieldWithValidations($validations);

        $this->form->addFormElement($anyField);

        $dependencies = $this->form->getDependencies();
        $this->assertSame($validations, $dependencies['validations'][$anyField->getName()]);
    }

    public function testGetFieldByName()
    {
        $textName = 'test_text';
        $dateName = 'test_date';
        $textInput = new TextInput(
            array(
                'name' => $textName
            )
        );
        $dateInput = new DateInput(
            array(
                'name' => $dateName
            )
        );
        $this->form->addFormElement($textInput);
        $this->form->addFormElement(new FieldSet(
            array(
                'fields' => array($dateInput)
            )
        ));

        $this->assertSame($textInput, $this->form->getFieldByName($textName));
        $this->assertSame($dateInput, $this->form->getFieldByName($dateName));
    }

    public function testValidationErrorsMappedToProperFields()
    {
        $textName = 'test_text';
        $dateName = 'test_date';
        $textInput = new TextInput(
            array(
                'name' => $textName
            )
        );
        $dateInput = new DateInput(
            array(
                'name' => $dateName
            )
        );
        $this->form->addFormElement($textInput);
        $this->form->addFormElement(new FieldSet(
            array(
                'fields' => array($dateInput)
            )
        ));

        $dateError = 'this is the date error message';
        $textError = 'this is the text error message';
        $errors = array(
            $dateName => $dateError,
            $textName => $textError
        );

        $this->form->mapErrorMessagesToFields($errors);

        $this->assertEquals($textError, $this->form->getFieldByName($textName)->getValidationError());
        $this->assertEquals($dateError, $this->form->getFieldByName($dateName)->getValidationError());
    }

    public function testMappingEmptyErrorsListCausesNoIssues()
    {
        $this->form->mapErrorMessagesToFields();
    }

    private function getAnyValidations()
    {
        return array('required');
    }

    private function getAnyField()
    {
        $field = $this->getMock('TextInput');

        $field->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('fieldName'));

        return $field;
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
