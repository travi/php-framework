<?php

namespace travi\framework\components\Forms;

use travi\framework\content\ContentObject;

abstract class Field extends ContentObject implements FormElement
{
    protected $validations = array();
    protected $name;
    protected $label;
    protected $value;
    protected $error;
    protected $type;
    protected $class;

    public function __construct($options)
    {
        $this->initializeLabel($options);
        $this->initializeName($options);
        $this->initializeValue($options);
        $this->initializeValidations($options);
    }

    public function getValidations()
    {
        return $this->validations;
    }

    public function setName($name)
    {
        $name = str_replace(' ', '_', strtolower($name));

        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->name;
    }

    public function setLabel($label)
    {
        $this->label = $label;
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

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function addValidation($validation)
    {
        array_push($this->validations, $validation);
    }

    public function getValidationError()
    {
        return $this->error;
    }

    public function setValidationError($message)
    {
        $this->error = $message;
    }

    public function isValid()
    {
        $value = trim($this->value);
        if (in_array('required', $this->getValidations()) && empty($value)) {
            $this->setValidationError($this->label . ' is required');
            return false;
        }

        return true;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param $options
     */
    protected function initializeValidations($options)
    {
        if (!empty($options['validations'])) {
            foreach ($options['validations'] as $validation) {
                $this->addValidation($validation);
            }
        }
    }

    /**
     * @param $options
     * @return mixed
     */
    protected function initializeLabel($options)
    {
        if (isset($options['label'])) {
            $this->label = $options['label'];
        }
    }

    /**
     * @param $options
     * @return mixed
     */
    protected function initializeName($options)
    {
        if (!empty($options['name'])) {
            $this->setName($options['name']);
        } elseif (isset($this->label)) {
            $this->setName($options['label']);
        }
    }

    /**
     * @param $options
     * @return mixed
     */
    protected function initializeValue($options)
    {
        if (isset($options['value'])) {
            $this->value = $options['value'];
        }
    }
}