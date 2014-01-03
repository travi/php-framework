<?php

use travi\framework\components\Forms\FieldSet;

class FieldSetTest extends PHPUnit_Framework_TestCase
{
    /** @var FieldSet */
    protected $fieldSet;

    protected function setUp()
    {
        $options = array();
        $options['legend'] = 'legend';

        $this->fieldSet = new FieldSet($options);
    }

    public function testGetLegend()
    {
        $this->assertSame('legend', $this->fieldSet->getLegend());
    }
}
?>
