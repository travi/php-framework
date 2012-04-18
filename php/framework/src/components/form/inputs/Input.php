<?php

require_once dirname(__FILE__).'/../Field.php';

abstract class Input extends ContentObject implements Field
{
    private $label;					//label associated with this field
    private $name;					//name attribute for this field
    protected $validations = array();	//list of validations
    private $type;					//type attribute for this field
    private $value;					//value attribute for this field
    private $class;					//class attribute for this field
    private $error;

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
    public function addValidation($validation)
    {
        array_push($this->validations, $validation);
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
    public function getName()
    {
        return $this->name;
    }
    public function getLabel()
    {
        return $this->label;
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
    public  function setLabel($label)
    {
        $this->label = $label;
    }
    public function getValidations()
    {
        return $this->validations;
    }

    public function setValidationError($message)
    {
        $this->error = $message;
    }

    public function getValidationError()
    {
        return $this->error;
    }

    public function isValid()
    {
        if (in_array('required', $this->getValidations()) && empty($this->value)) {
            return false;
        }

        return true;
    }
}