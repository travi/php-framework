<?php

use Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Travi\framework\components\Forms\Form;
use Travi\framework\components\Forms\inputs\TextInput;

class FormContext extends BehatContext
{
    /** @var Form */
    private $form;

    /**
     * @BeforeScenario
     */
    public function inititalizeForm()
    {
        $_SERVER['REQUEST_URI'] = 'something';
        $this->form = new Form();
    }

    /**
     * @AfterScenario
     */
    public function tearDown()
    {
        $_SERVER['REQUEST_URI'] = null;
        $this->form = null;
    }


    /**
     * @Given /^"([^"]*)" with value "([^"]*)"$/
     */
    public function withValue($label, $value)
    {
        $this->form->addFormElement(
            new TextInput(
                array(
                    'label' => $label,
                    'value' => $value,
                    'validations' => array('required')
                )
            )
        );
    }

    /**
     * @When /^validation is checked$/
     */
    public function validationIsChecked()
    {
        $this->form->hasErrors();
    }

    /**
     * @Then /^"([^"]*)" should report validation error "([^"]*)"$/
     */
    public function shouldReportValidationError($fieldName, $error)
    {
        assertEquals($error, $this->form->getFieldByName($fieldName)->getValidationError());
    }
}