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

    public function __construct($settings = array())
    {
        $this->label = $settings['label'];
        if (!empty($settings['name'])) {
            $this->name = $settings['name'];
        } else {
            $this->name = strtolower($settings['label']);
        }
        if (isset($settings['value'])) {
            $this->value = $settings['value'];
        }
        $this->settings = $settings;
        if (isset($settings['options'])) {
            $this->optionAdder($settings['options']);
        }
        if (!empty($settings['validations'])) {
            foreach ($settings['validations'] as $validation) {
                $this->addValidation($validation);
            }
        }
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
                    false
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

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
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
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    public function getTemplate()
    {
        return $this->template;
    }
}

 
