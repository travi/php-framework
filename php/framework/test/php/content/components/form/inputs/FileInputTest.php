<?php
require_once 'PHPUnit/Autoload.php';

require_once dirname(__FILE__).'/../../../../../../src/components/form/inputs/FileInput.php';

class FileInputTest extends PHPUnit_Framework_TestCase
{
    /** @var FileInput */
    protected $object;

    protected function setUp()
    {
        $this->object = new FileInput;
    }

    public function testClassName()
    {
        $this->assertSame('fileInput', $this->object->getClass());
    }

    public function testType()
    {
        $this->assertSame('file', $this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/inputWithLabel.tpl', $this->object->getTemplate());
    }
}
?>
