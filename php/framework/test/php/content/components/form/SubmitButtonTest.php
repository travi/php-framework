<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../src/components/form/SubmitButton.php';

class SubmitButtonTest extends PHPUnit_Framework_TestCase
{
    /** @var SubmitButton */
    protected $object;

    protected function setUp()
    {
        $this->object = new SubmitButton;
    }

    public function testClassName()
    {
        $this->assertSame('submitButton', $this->object->getClass());
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
        $this->assertSame('submit', $this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/input.tpl', $this->object->getTemplate());
    }

    public function testGetJavaScripts()
    {
        $this->assertSame(array('jqueryUi'), $this->object->getJavaScripts());
    }

    public function testGetJsInits()
    {
        $this->assertSame(array('$("input[type=submit]").button()'), $this->object->getJsInits());
    }

    public function testGetName()
    {
        $this->assertSame('submit', $this->object->getName());
    }
}
?>
