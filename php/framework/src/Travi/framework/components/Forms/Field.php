<?php

namespace Travi\framework\components\Forms;

use Travi\framework\content\ContentObject;

abstract class Field extends ContentObject implements FormElement
{
    protected $validations = array();
    protected $name;
    protected $label;
    protected $value;
    protected $error;

    public function getValidations()
    {
        return $this->validations;
    }

    public function getName()
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
        if (in_array('required', $this->getValidations()) && empty($this->value)) {
            $this->setValidationError($this->label . ' is required');
            return false;
        }

        return true;
    }
}