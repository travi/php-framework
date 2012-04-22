<?php
require_once dirname(__FILE__) . '/../../../php/framework/objects/content/form.class.php';
require_once dirname(__FILE__) . '/../../../php/framework/objects/content/list/entityBlock.class.php';
require_once dirname(__FILE__) . '/../../../php/framework/objects/content/list/entityList.class.php';

abstract class AbstractMapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @abstract
     * @param $form Form
     */
    protected abstract function assertFormHasProperFields($form);
    /**
     * @abstract
     * @param $form Form
     */
    protected abstract function assertFieldValuesSetCorrectly($form);

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
                $this->assertEquals($option['value'], $actualOption['value']);
            } else {
                $optionKey = $option;
            }

            $this->assertEquals($optionKey, $actualOption['option']);
        }
    }

}
