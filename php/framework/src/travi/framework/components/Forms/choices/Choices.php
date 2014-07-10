<?php

namespace travi\framework\components\Forms\choices;

use travi\framework\components\Forms\Field;

abstract class Choices extends Field
{
    protected $type;
    protected $class;
    protected $template;

    protected $settings = array();
    protected $options  = array();

    public function __construct($settings=array())
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
        $this->optionAdder($settings['options']);
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
                    $this->isThisOptionSelected($option)
                );
            } else {
                $this->addOption($option, null, $this->isThisOptionSelected($option));
            }
        }
    }

    private function isThisOptionSelected($option)
    {
        if (isset($this->value) && ($this->value === $option['value'])) {
            return true;
        } elseif ($this->value === $option) {
            return true;
        } elseif (empty($option['value']) && ($this->value === $option['option'])) {
            return true;
        } elseif (is_array($option) && isset($option['selected']) && $option['selected']) {
            return true;
        } else {
            return false;
        }
    }

    public function addOption($option, $value="", $selected=false, $disabled=false)
    {
        $optionAR = array(
            'option'    => $option,
            'value'     => $value,
            'selected'  => $selected,
            'disabled'  => $disabled
        );

        array_push($this->options, $optionAR);
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
                $option['selected'] = true;
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

 
