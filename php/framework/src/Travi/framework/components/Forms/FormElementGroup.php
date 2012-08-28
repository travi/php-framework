<?php

namespace Travi\framework\components\Forms;

use Travi\framework\DependantObject,
    Travi\framework\content\ContentObject,
    Travi\framework\components\Forms\FormElement,
    Travi\framework\components\Forms\inputs\Input,
    Travi\framework\components\Forms\inputs\TextInput;

abstract class FormElementGroup extends ContentObject implements FormElement
{
    /** @var FormElement[] */
    private $formElements = array();

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
            } elseif (is_a($formElement, 'Travi\\framework\\components\\Forms\\FormElementGroup')) {
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
            if (is_a($formElement, 'Travi\\framework\\components\\Forms\\FormElementGroup')) {
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
                return false;
            }
        }

        return true;
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
            if (is_a($element, 'Travi\\framework\\components\\Forms\\Field')
                && $element->getName() === $fieldName
            ) {
                return $element;
            } elseif (is_a($element, 'Travi\\framework\\components\\Forms\\FormElementGroup')) {
                $field = $element->getFieldByName($fieldName);

                if (!empty($field)) {
                    return $field;
                }
            }
        }
    }
}