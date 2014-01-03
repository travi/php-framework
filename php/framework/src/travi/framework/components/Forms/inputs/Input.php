<?php

namespace travi\framework\components\Forms\inputs;

use travi\framework\components\Forms\Field;

abstract class Input extends Field
{
    private $type;
    protected $class;

    public function __construct($options)
    {
        if (isset($options['label'])) {
            $this->label = $options['label'];
        }
        if (!empty($options['name'])) {
            $this->setName($options['name']);
        } else {
            $this->setName($options['label']);
        }
        if (isset($options['value'])) {
            $this->value = $options['value'];
        }
        if (!empty($options['validations'])) {
            foreach ($options['validations'] as $validation) {
                $this->addValidation($validation);
            }
        }
        $this->setTemplate('components/form/inputWithLabel.tpl');
    }
    public function setName($name)
    {
        $name = str_replace(' ', '_', strtolower($name));

        //ensure value is not "name" or "id" (expandos)
        if ($name === 'name' || $name === 'id') {
            $name .= '_value';
        }

        $this->name = $name;
    }
    public function setType($type)
    {
        $this->type = $type;
    }
    public function getType()
    {
        return $this->type;
    }
    public function setValue($value)
    {
        $this->value = $value;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function setClass($class)
    {
        $this->class = $class;
    }
    public function getClass()
    {
        return $this->class;
    }
}