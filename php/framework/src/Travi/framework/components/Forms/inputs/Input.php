<?php

namespace Travi\framework\components\Forms\inputs;

use Travi\framework\components\Forms\Field;

abstract class Input extends Field
{
    private $type;					//type attribute for this field
    protected $class;					//class attribute for this field

    public function __construct($options)
    {
        $this->label = $options['label'];
        if (!empty($options['name'])) {
            $this->setName($options['name']);
        } else {
            $this->setName($options['label']);
        }
        $this->value = $options['value'];
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