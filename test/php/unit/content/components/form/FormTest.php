<?php

use travi\framework\components\Forms\Form,
    travi\framework\components\Forms\FieldSet,
    travi\framework\components\Forms\inputs\Input,
    travi\framework\components\Forms\inputs\DateInput,
    travi\framework\components\Forms\inputs\TextInput;
use travi\framework\components\Forms\SubmitButton;
use travi\framework\view\objects\LinkView;

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
        $this->assertContains(
            '/resources/thirdparty/travi-styles/dist/css/form/travi-form.css',
            $dependencies['styles']
        );
    }

    public function testThatDependenciesForActionsIncludedInFormDependencies()
    {
        $this->form->addAction(new SubmitButton());

        $dependencies = $this->form->getDependencies();

        $this->assertContains('buttons', $dependencies['scripts']);
    }

    public function testValidationScriptNotAddedToDependencyListWhenNoValidationDefined()
    {
        $anyField = $this->getAnyField();

        $this->form->addFormElement($anyField);

        $dependencies = $this->form->getDependencies();
        $this->assertNotContains('validation', $dependencies['scripts']);

    }

    public function testValidationScriptAddedToDependencyListWhenValidationDefined()
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
        $this->assertEquals($validations, $dependencies['validations'][$anyField->getName()]);
    }

    public function testThatValidationListDoesNotContainListForFieldWithNoRules()
    {
        $this->form->addFormElement($this->getAnyField());

        $dependencies = $this->form->getDependencies();
        $this->assertEquals(array(), $dependencies['validations']);
    }

    public function testThatActionInputCanBeRetrievedByName()
    {
        $action = new SubmitButton();

        $this->form->addAction(new LinkView('some text', 'some href'));
        $this->form->addAction($action);

        $this->assertSame($action, $this->form->getFieldByName('submit'));
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

    public function testThatErrorMappedProperlyToFieldWithUnsafelName()
    {
        $name = 'name';
        $error = 'some error message';

        $this->form->addFormElement(
            new TextInput(
                array(
                    'name' => $name
                )
            )
        );

        $this->form->mapErrorMessagesToFields(
            array(
                $name => $error
            )
        );

        $this->assertEquals($error, $this->form->getFieldByName($name)->getValidationError());

    }

    public function testMappingEmptyErrorsListCausesNoIssues()
    {
        $this->form->mapErrorMessagesToFields();
    }

    public function testHasErrorsReturnsFalseWhenValid()
    {
        $field = $this->getMock('travi\\framework\\components\\Forms\\inputs\\TextInput');
        $field->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));

        $this->form->addFormElement($field);

        $this->assertFalse($this->form->hasErrors());
    }

    public function testHasErrorsReturnsTrueWhenInvalid()
    {
        $field1 = $this->getMock('travi\\framework\\components\\Forms\\inputs\\TextInput');
        $field1->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->form->addFormElement($field1);

        $field2 = $this->getMock('travi\\framework\\components\\Forms\\inputs\\TextInput');
        $field2->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(false));

        $this->form->addFormElement($field2);

        $this->assertTrue($this->form->hasErrors());
    }

    private function getAnyValidations()
    {
        return array('required');
    }

    private function getAnyField()
    {
        $field = $this->getMock('travi\\framework\\components\\Forms\\inputs\\TextInput');

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
