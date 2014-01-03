<?php

use travi\framework\components\Forms\inputs\DateInput;

class DateInputTest extends PHPUnit_Framework_TestCase
{
    /** @var DateInput */
    protected $object;

    protected function setUp()
    {
        $this->object = new DateInput;
    }

    public function testDefaultInit()
    {
        $this->assertSame('date', $this->object->getType());
        $this->assertSame('textInput datepicker', $this->object->getClass());
        $this->assertSame(array('jqueryUi'), $this->object->getJavaScripts());
        $this->assertSame(
            array(
                 "$('input.datepicker').datepicker({
                dateFormat: 'yy-mm-dd',
                buttonImage: '/resources/thirdparty/travi-styles/img/calendar.gif',
                buttonImageOnly: true, showOn: 'both'
            });"
            ),
            $this->object->getJsInits()
        );
    }
}
?>
