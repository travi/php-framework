<?php

require_once __DIR__ . '/../FieldTest.php';

use travi\framework\components\Forms\inputs\OpenIdInput;

class OpenIdInputTest extends FieldTest {
    public function setUp()
    {
        $this->field = new OpenIdInput();
    }

    public function testThatFieldIsInitializedCorrectly()
    {
        $this->assertEquals('OpenID', $this->field->getLabel());
        $this->assertEquals('openid_identifier', $this->field->getName());
        $this->assertEquals("textInput open-id", $this->field->getClass());
        $this->assertEquals('text', $this->field->getType());
    }

    public function testThatOptionsArePassedToParentConstructor()
    {
        $validations = array('required');

        $openIdInput = new OpenIdInput(array('validations' => $validations));

        $this->assertEquals($validations, $openIdInput->getValidations());
    }

    public function testThatDefaultOptionsCanBeOverridden()
    {
        $label = 'Different Label';
        $name = 'different_name';

        $openIdInput = new OpenIdInput(
            array(
                'label' => $label,
                'name' => $name
            )
        );

        $this->assertEquals($label, $openIdInput->getLabel());
        $this->assertEquals($name, $openIdInput->getName());
    }
}
