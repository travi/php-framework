<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/../../../../../src/components/form/FormElementGroup.php';
require_once dirname(__FILE__).'/../../../../../src/components/form/FieldSet.php';
require_once dirname(__FILE__).'/../../../../../src/components/form/inputs/TextInput.php';
require_once dirname(__FILE__).'/../../../../../src/components/form/inputs/FileInput.php';

class FormElementGroupTest extends PHPUnit_Framework_TestCase
{
    /** @var FormElementGroup */
    private $group;

    protected function setUp()
    {
        $this->group = $this->getMockForAbstractClass('FormElementGroup');
    }

    public function testDoesNotContainFileInput()
    {
        $this->assertFalse($this->group->containsFormElementType("FileInput"));
    }

    public function testInnerGroupDoesNotContainFileInput()
    {
        $fileInputType = 'FileInput';

        /** @var $anyGroup FieldSet */
        $anyGroup = $this->getAnyGroup();
        $anyGroup->expects($this->once())
            ->method('containsFormElementType')
            ->with($this->equalTo($fileInputType))
            ->will($this->returnValue(false));

        $this->group->addFormElement($anyGroup);
        
        $this->assertFalse($this->group->containsFormElementType("FileInput"));
    }

    public function testContainsFileInput()
    {
        $fileInputType = 'FileInput';

        $fileInput = $this->getMock($fileInputType);

        $this->group->addFormElement($fileInput);

        $this->assertTrue($this->group->containsFormElementType($fileInputType));
    }

    public function testContainsFileInputInAGroup()
    {
        $fileInputType = 'FileInput';

        /** @var $anyGroup FieldSet */
        $anyGroup = $this->getAnyGroup();
        $anyGroup->expects($this->once())
            ->method('containsFormElementType')
            ->with($this->equalTo($fileInputType))
            ->will($this->returnValue(true));

        $this->group->addFormElement($anyGroup);

        $this->assertTrue($this->group->containsFormElementType($fileInputType));
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

        $this->markTestIncomplete();
    }

    private function getAnyValidations()
    {
        return array('required');
    }

    private function getAnyField()
    {
        return $this->getMock('TextInput');
    }

    private function getAnyGroup()
    {
        return $this->getMock('Fieldset');
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