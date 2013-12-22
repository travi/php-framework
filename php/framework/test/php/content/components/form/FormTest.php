<?php

use Travi\framework\components\Forms\Form,
    Travi\framework\components\Forms\FieldSet,
    Travi\framework\components\Forms\inputs\Input,
    Travi\framework\components\Forms\inputs\DateInput,
    Travi\framework\components\Forms\inputs\TextInput;

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
        $anyField = $this->getMock(Form::FORMS_NAMESPACE . 'inputs\\FileInput');

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
        $this->assertContains('/resources/thirdparty/travi-styles/css/travi-form.css', $dependencies['styles']);
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
        $this->form->addFormElement(
            new FieldSet(
                array(
                    'fields' => array($dateInput)
                )
            )
        );

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

    public function testHasErrorsReturnsFalseWhenValid()
    {
        $field = $this->getMock('Travi\\framework\\components\\Forms\\inputs\\TextInput');
        $field->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->form->addFormElement($field);

        $this->assertFalse($this->form->hasErrors());
    }

    public function testHasErrorsReturnsTrueWhenInvalid()
    {
        $field = $this->getMock('Travi\\framework\\components\\Forms\\inputs\\TextInput');
        $field->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->form->addFormElement($field);

        $this->assertTrue($this->form->hasErrors());
    }

    private function getAnyValidations()
    {
        return array('required');
    }

    private function getAnyField()
    {
        $field = $this->getMock('Travi\\framework\\components\\Forms\\inputs\\TextInput');

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
