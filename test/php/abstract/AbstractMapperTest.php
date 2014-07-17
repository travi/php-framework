<?php

use travi\framework\collection\EntityList;
use travi\framework\components\Forms\choices\RadioButtons;
use travi\framework\components\Forms\Form;

abstract class AbstractMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @abstract
     * @param $form Form
     */
    protected abstract function assertFormHasProperFields($form);

    /**
     * @param $values
     * @param $form Form
     */
    protected function assertFieldValuesSetCorrectly($values, $form)
    {
        $count = 0;
        foreach ($values as $field => $value) {
            $this->assertEquals($value, $form->getFieldByName($field)->getValue());
            $count++;
        }
        $this->assertEquals(sizeof($values), $count);
    }

    /**
     * @param $field RadioButtons
     * @param $options
     */
    protected function assertRadioButtonOptionsEqualTo($options, $field)
    {
        $actualOptions = $field->getOptions();
        $this->assertOptionsEqual($options, $actualOptions);
    }

    protected function assertChoicesSelectionOptionIs($value, $options)
    {
        $selected = 'provided selection (' . $value . ') is not available';
        foreach ($options as $option) {
            if ($option['selected'] === true) {
                $selected = $option['option'];
            }
        }
        $this->assertEquals($value, $selected);
    }

    protected function assertSelectionBoxOptionsEqualTo($expectedOptions, $actualOptions)
    {
        array_shift($actualOptions);
        $this->assertOptionsEqual($expectedOptions, $actualOptions);
    }

    protected function assertOptionsEqual($expectedOptions, $actualOptions)
    {
        foreach ($expectedOptions as $index => $option) {
            $actualOption = $actualOptions[$index];

            if (is_array($option)) {
                $optionKey = $option['option'];
                $this->assertEquals($option['value'], $actualOption->value);
            } else {
                $optionKey = $option;
            }

            $this->assertEquals($optionKey, $actualOption->option);
        }
    }
}
