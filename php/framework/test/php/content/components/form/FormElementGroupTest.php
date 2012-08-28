<?php

use Travi\framework\components\Forms\FormElementGroup,
    Travi\framework\components\Forms\FieldSet,
    Travi\framework\components\Forms\inputs\Input,
    Travi\framework\components\Forms\inputs\TextInput,
    Travi\framework\components\Forms\inputs\FileInput,
    Travi\framework\components\Forms\inputs\DateInput;

class FormElementGroupTest extends PHPUnit_Framework_TestCase
{
    private $fileInputType = 'Travi\\framework\\components\\Forms\\inputs\\FileInput';
    /** @var FormElementGroup */
    private $group;

    protected function setUp()
    {
        $this->group = $this->getMockForAbstractClass(
            'Travi\\framework\\components\\Forms\\FormElementGroup'
        );
    }

    public function testDoesNotContainFileInput()
    {
        $this->assertFalse($this->group->containsFormElementType($this->fileInputType));
    }

    public function testInnerGroupDoesNotContainFileInput()
    {
        /** @var $anyGroup FieldSet */
        $anyGroup = $this->getAnyGroup();
        $anyGroup->expects($this->once())
            ->method('containsFormElementType')
            ->with($this->equalTo($this->fileInputType))
            ->will($this->returnValue(false));

        $this->group->addFormElement($anyGroup);

        $this->assertFalse($this->group->containsFormElementType($this->fileInputType));
    }

    public function testContainsFileInput()
    {
        $this->group->addFormElement($this->getMock($this->fileInputType));

        $this->assertTrue($this->group->containsFormElementType($this->fileInputType));
    }

    public function testContainsFileInputInAGroup()
    {
        /** @var $anyGroup FieldSet */
        $anyGroup = $this->getAnyGroup();
        $anyGroup->expects($this->once())
            ->method('containsFormElementType')
            ->with($this->equalTo($this->fileInputType))
            ->will($this->returnValue(true));

        $this->group->addFormElement($anyGroup);

        $this->assertTrue($this->group->containsFormElementType($this->fileInputType));
    }

    public function testGroupAcceptsFields()
    {
        $anyField = $this->getAnyField();

        $this->group->addFormElement($anyField);

        $this->assertContains($anyField, $this->group->getFormElements());
    }

    public function testGroupAcceptsOtherGroups()
    {
        $anyGroup = $this->getAnyGroup();

        $this->group->addFormElement($anyGroup);

        $this->assertContains($anyGroup, $this->group->getFormElements());
    }

    public function testValidationsReturnedFromContainedField()
    {
        $validations = $this->getAnyValidations();
        $anyField = $this->getAnyFieldWithValidations($validations);

        $this->group->addFormElement($anyField);

        $vals = $this->group->getValidations();
        $this->assertNotNull($vals);
        $this->assertSame($validations, $vals['inputName']);
    }

    public function testValidationsReturnedFromContainedGroup()
    {
        $validations = $this->getAnyValidations();
        $inputName = 'inputName';

        /** @var $anyGroup FieldSet */
        $anyGroup = $this->getAnyGroup();
        $anyGroup->expects($this->once())
            ->method('getValidations')
            ->will($this->returnValue(array($inputName => $validations)));

        $this->group->addFormElement($anyGroup);

        $vals = $this->group->getValidations();
        $this->assertNotNull($vals);
        $this->assertSame($validations, $vals[$inputName]);
    }

    public function testDependenciesReturnedFromContainedField()
    {
        $anyField = $this->getAnyField();
        $anyField->expects($this->once())
            ->method('getDependencies')
            ->will($this->returnValue(array('scripts' => array('jsDep'))));

        $this->group->addFormElement($anyField);

        $dependencies = $this->group->getDependencies();
        $this->assertContains('jsDep', $dependencies['scripts']);
    }

    public function testDependenciesReturnedFromContainedGroup()
    {
        $anyGroup = $this->getAnyField();
        $anyGroup->expects($this->once())
            ->method('getDependencies')
            ->will($this->returnValue(array('scripts' => array('jsDep'))));

        $this->group->addFormElement($anyGroup);

        $dependencies = $this->group->getDependencies();
        $this->assertContains('jsDep', $dependencies['scripts']);
    }

    public function testGetFieldByName()
    {
        $textName = 'test_text';
        $textNameSetToName = 'name';
        $dateName = 'test_date';
        $textInput = new TextInput(
            array(
                'name' => $textName
            )
        );
        $textInput2 = new TextInput(
            array(
                'name' => $textNameSetToName
            )
        );
        $dateInput = new DateInput(
            array(
                'name' => $dateName
            )
        );
        $this->group->addFormElement($textInput);
        $this->group->addFormElement($textInput2);
        $this->group->addFormElement(
            new FieldSet(
                array(
                    'fields' => array($dateInput)
                )
            )
        );

        $this->assertSame($textInput, $this->group->getFieldByName($textName));
        $this->assertSame($dateInput, $this->group->getFieldByName($dateName));
        $this->assertSame($textInput2, $this->group->getFieldByName($textNameSetToName));
    }

    private function getAnyValidations()
    {
        return array('required');
    }

    private function getAnyField()
    {
        return $this->getMock('Travi\\framework\\components\\Forms\\inputs\\TextInput');
    }

    private function getAnyGroup()
    {
        return $this->getMock('Travi\\framework\\components\\Forms\\FieldSet');
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
        $field->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('inputName'));

        return $field;
    }
}