<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/src/components/form/HiddenInput.php';

/**
 * Test class for HiddenInput.
 * Generated by PHPUnit on 2011-01-26 at 17:24:32.
 */
class HiddenInputTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var HiddenInput
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new HiddenInput;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    public function testClassName()
    {
        $this->assertNull($this->object->getClass());
    }

    public function testType()
    {
        $this->assertSame('hidden', $this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/input.tpl', $this->object->getTemplate());
    }
}
?>
