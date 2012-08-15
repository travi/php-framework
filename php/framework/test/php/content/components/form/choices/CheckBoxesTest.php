<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/choices/CheckBoxes.php';

class CheckBoxesTest extends PHPUnit_Framework_TestCase
{
    /** @var CheckBoxes */
    protected $object;

    protected function setUp()
    {
        $this->object = new CheckBoxes;
    }

    public function testDefaults()
    {
        $this->assertSame('components/form/choices.tpl', $this->object->getTemplate());
        $this->assertSame('checkbox', $this->object->getType());
        $this->assertSame('checkbox', $this->object->getClass());
    }
}
?>
