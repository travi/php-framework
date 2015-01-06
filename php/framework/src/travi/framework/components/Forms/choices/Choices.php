<?php

namespace travi\framework\components\Forms\choices;

use travi\framework\components\Forms\Field;
use travi\framework\view\objects\inputs\Option;

abstract class Choices extends Field
{
    protected $type;
    protected $class;
    protected $template;

    protected $settings = array();
    protected $options  = array();

    public function __construct($options = array())
    {
        $this->initializeLabel($options);
        $this->initializeName($options);
        $this->initializeValue($options);
        $this->initializeOptions($options);
        $this->initializeValidations($options);
            $this->settings = $options;

        $this->setTemplate('components/form/choices.tpl');
    }

    public function addOptions($options)
    {
        $this->optionAdder($options);
    }

    protected function optionAdder($options=array())
    {
        foreach ($options as $option) {
            if (is_array($option)) {
                $this->addOption(
                    $option['label'],
                    $option['value'],
                    isset($option['selected']) ? $option['selected'] : false
                );
            } else {
                $this->addOption($option, null, $this->isThisOptionSelected($option));
            }
        }
    }

    /**
     * @param Option $option
     * @return bool
     */
    private function isThisOptionSelected($option)
    {
        if (isset($this->value) && ($this->value === $option->value)) {
            return true;
        } elseif (is_string($option)) {
            return false;
        } else {
            return $option->selected;
        }
    }

    public function addOption($text, $value = null, $selected = false)
    {
        array_push($this->options, new Option($text, $value, $selected));
    }

    public function getOptions()
    {
        foreach ($this->options as &$option) {
            if ($this->isThisOptionSelected($option)) {
                $option->selected = true;
            }
        }

        return $this->options;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param $options
     * @return mixed
     */
    private function initializeOptions($options)
    {
        if (isset($options['options'])) {
            $this->optionAdder($options['options']);
        }
    }


}