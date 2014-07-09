<?php

namespace travi\framework\components\Forms;

use travi\framework\DependantObject,
    travi\framework\content\ContentObject;

abstract class FormElementGroup extends ContentObject implements FormElement
{
    /** @var FormElement[] */
    private $formElements = array();
    private $errorCount   = 0;

    public function addFormElement($formElement)
    {
        array_push($this->formElements, $formElement);
    }

    public function getFormElements()
    {
        return $this->formElements;
    }

    public function containsFormElementType($type)
    {
        foreach ($this->formElements as $formElement) {
            if (is_a($formElement, $type)) {
                return true;
            } elseif (is_a($formElement, Form::FORMS_NAMESPACE . 'FormElementGroup')) {
                /** @var $formElement FormElementGroup */
                if ($formElement->containsFormElementType($type)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getValidations()
    {
        $validations = array();

        foreach ($this->formElements as $formElement) {
            if (is_a($formElement, 'travi\\framework\\components\\Forms\\FormElementGroup')) {
                $validations = array_merge($validations, $formElement->getValidations());
            } else {
                $validations[$formElement->getName()] = $formElement->getValidations();            
            }
        }

        return $validations;
    }

    public function isValid()
    {
        /** @var $formElement FormElement */
        foreach ($this->formElements as $formElement) {
            if (!$formElement->isValid()) {
                $this->errorCount++;
            }
        }

        return $this->errorCount === 0;
    }
    
    public function getDependencies()
    {
        foreach ($this->getFormElements() as $formElement) {
            /** @var $formElement DependantObject */
            $this->addDependencies($formElement->getDependencies());
        }
        return parent::getDependencies();
    }

    /**
     * @param $fieldName
     * @return Field
     */
    public function getFieldByName($fieldName)
    {
        if ('name' === $fieldName) {
            $fieldName = 'name_value';
        }

        $formElements = $this->getFormElements();

        /** @var $element Field */
        foreach ($formElements as $element) {
            if (is_a($element, 'travi\\framework\\components\\Forms\\Field')
                && $element->getName() === $fieldName
            ) {
                return $element;
            } elseif (is_a($element, 'travi\\framework\\components\\Forms\\FormElementGroup')) {
                /** @var FormElementGroup $element */
                $field = $element->getFieldByName($fieldName);

                if (!empty($field)) {
                    return $field;
                }
            }
        }
    }

    public function getErrors()
    {
        $errors = array();

        foreach ($this->getFormElements() as $element) {
            if (is_a($element, 'travi\\framework\\components\\Forms\\Field')) {
                /** @var $element Field */
                $validationError = $element->getValidationError();
                if ($validationError) {
                    $errors[$element->getName()] = $validationError;
                }
            } elseif (is_a($element, 'travi\\framework\\components\\Forms\\FormElementGroup')) {
                /** @var $element FormElementGroup */
                $errors = array_merge($errors, $element->getErrors());
            }
        }

        return $errors;
    }
}
