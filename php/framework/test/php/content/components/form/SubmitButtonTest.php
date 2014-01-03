<?php

use travi\framework\components\Forms\SubmitButton,
    travi\framework\components\Forms\inputs\Input;

class SubmitButtonTest extends PHPUnit_Framework_TestCase
{
    /** @var SubmitButton */
    protected $submitButton;

    protected function setUp()
    {
        $this->submitButton = new SubmitButton;
    }

    public function testClassName()
    {
        $this->assertSame('submitButton', $this->submitButton->getClass());
    }

    public function testAlternateClassName()
    {
        $button = new SubmitButton(array('class' => 'altSubmitButton'));
        $this->assertSame('altSubmitButton', $button->getClass());
    }

    /**
     * @todo Refactor SubmitButton to do this more intentionally.
     */
    public function testClassNameOutsideFieldSet()
    {
        $button = new SubmitButton();

        $button->isOuterButton(true);

        $this->assertSame('submitButton outerButton', $button->getClass());
    }

    public function testType()
    {
        $this->assertSame('submit', $this->submitButton->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/input.tpl', $this->submitButton->getTemplate());
    }

    public function testGetJavaScripts()
    {
        $this->assertSame(array('jqueryUi'), $this->submitButton->getJavaScripts());
    }

    public function testGetJsInits()
    {
        $this->assertSame(array('$("input[type=submit]").button()'), $this->submitButton->getJsInits());
    }

    public function testGetName()
    {
        $this->assertSame('submit', $this->submitButton->getName());
    }
}
?>
