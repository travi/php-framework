<?php
require_once 'PHPUnit/Framework.php';

require_once '/Users/travi/development/include/php/framework/src/components/form/inputs/RichTextArea.php';

/**
 * Test class for RichTextArea.
 * Generated by PHPUnit on 2011-01-26 at 17:40:43.
 */
class RichTextAreaTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RichTextArea
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new RichTextArea;
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
        $this->assertSame('textInput richEditor', $this->object->getClass());
    }

    public function testType()
    {
        $this->assertNull($this->object->getType());
    }

    public function testTemplate()
    {
        $this->assertSame('components/form/richTextArea.tpl', $this->object->getTemplate());
    }

    public function testGetJavaScripts()
    {
        $this->assertSame(array('wymEditor','wymEditor-fullScreen'), $this->object->getJavaScripts());
    }

    public function testGetJsInits()
    {
        $this->assertSame(array("$('textarea.richEditor').wymeditor({
                                                        skin: 'silver',
                                                        updateSelector: '#Submit',
                                                        postInit: function (wym) {
                                                            wym.fullscreen();
                                                        }
                                                    });"), $this->object->getJsInits());
    }
}
?>