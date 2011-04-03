<?php

require_once(dirname(__FILE__).'/../../../objects/content/contentObject.class.php');
require_once(dirname(__FILE__).'/FormElement.php');
 
abstract class FormElementGroup extends contentObject implements FormElement
{
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
                $validations = array_merge($validations,$formElement->getValidations());
            } else {
                $validations[$formElement->getName()] = $formElement->getValidations();            
            }
        }

        return $validations;
    }
    
    public function getDependencies()
    {
        foreach ($this->getFormElements() as $formElement) {
            $this->checkDependencies($formElement);
        }
        return parent::getDependencies();
    }
}
