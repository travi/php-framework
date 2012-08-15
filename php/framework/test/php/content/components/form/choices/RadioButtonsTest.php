<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/choices/RadioButtons.php';

class RadioButtonsTest extends PHPUnit_Framework_TestCase
{
    /** @var RadioButtons */
    protected $object;

    protected function setUp()
    {
        $this->object = new RadioButtons;
    }

    public function testDefaults()
    {
        $this->assertSame('components/form/choices.tpl', $this->object->getTemplate());
        $this->assertSame('radio', $this->object->getType());
        $this->assertSame('radioButton', $this->object->getClass());
    }
}
?>
