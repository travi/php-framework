<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../src/components/form/NoteArea.php';

class NoteAreaTest extends PHPUnit_Framework_TestCase
{
    /** @var NoteArea */
    protected $object;

    protected function setUp()
    {
        $this->object = new NoteArea(array('label' => 'label', 'content' => 'content'));
    }

    public function testDefaultInit()
    {
        $this->assertSame('label', $this->object->getLabel());
        $this->assertSame('content', $this->object->getContent());
        $this->assertSame('components/form/noteArea.tpl', $this->object->getTemplate());
    }

    public function testGetValidations()
    {
        $this->assertSame(array(), $this->object->getValidations());
    }
}
?>
