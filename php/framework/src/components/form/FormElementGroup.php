<?php

require_once dirname(__FILE__).'/../../../objects/content/contentObject.class.php';
require_once dirname(__FILE__).'/FormElement.php';
 
abstract class FormElementGroup extends contentObject implements FormElement
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
            } elseif (is_a($formElement, 'FormElementGroup')) {
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
            if (is_a($formElement, "FormElementGroup")) {
                $validations = array_merge($validations, $formElement->getValidations());
            } else {
                $validations[$formElement->getName()] = $formElement->getValidations();            
            }
        }

        return $validations;
    }
    
    public function getDependencies()
    {
        foreach ($this->getFormElements() as $formElement) {
            /** @var $formElement DependantObject */
            $this->addDependencies($formElement->getDependencies());
        }
        return parent::getDependencies();
    }

    //    public function checkDependencies($object)
    //    {
    //        foreach ($this->getFormElements() as $formElement) {
    //            $this->checkDependencies($formElement);
    //        }
    //        return parent::checkDependencies($object);
    //    }

    /**
     * @param $fieldName
     * @return Field
     */
    public function getFieldByName($fieldName)
    {
        $formElements = $this->getFormElements();

        foreach ($formElements as $element) {
            if (is_a($element, 'Field') && $element->getName() === $fieldName) {
                return $element;
            } elseif (is_a($element, 'FormElementGroup')) {
                $field = $element->getFieldByName($fieldName);

                if (!empty($field)) {
                    return $field;
                }
            }
        }
    }
}
