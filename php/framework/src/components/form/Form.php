<?php

require_once(dirname(__FILE__).'/../../../objects/content/contentObject.class.php');

class Form extends ContentObject
{
    private $name;
    private $method;
    private $action;
    private $encodingType;
    private $formElements = array();
    private $debug;

    public function __construct($options)
    {
        $this->debug = $options['debug'];
        $this->name = $options['name'];
        if (!empty($options['method'])) {
            $this->setMethod($options['method']);
        } else {
            $this->method = 'post';
        }
        if (!empty($options['action'])) {
            $this->action = $options['action'];
        } else {
            $this->action = htmlentities($_SERVER['REQUEST_URI'] . "#Results");
        }

        if (!empty($options['fieldsets'])) {
            foreach ($options['fieldsets'] as $formElement) {
                if (!empty($formElement['fields'])) {
                    $this->addFormElement(new Fieldset($formElement));
                } else if (!empty($formElement['type'])) {
                    $this->addFormElement(new $formElement['type']($formElement));
                }
            }
        }

        $this->addStyleSheet('/resources/shared/css/travi.form.css');
        $this->addJavaScript('formAlign');
        $this->addJsInit("$('form[name=\"".$this->name."\"]').alignFields();");
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getEncType()
    {
        return $this->encodingType;
    }

    public function getFormElements()
    {
        return $this->formElements;
    }

    public function addFormElement($formElement)
    {
        array_push($this->formElements, $formElement);
    }

    public function contains($type)
    {
        foreach ($this->formElements as $formElement) {
            if (is_a($formElement, "Fieldset")) {
                if ($formElement->contains($type)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getDependencies()
    {
        $validations = $this->getValidations();
        foreach ($this->formElements as $formElement)
        {
            $this->checkDependencies($formElement);
        }
        $deps = parent::getDependencies();
        if (!empty($validations)) {
            $deps['validations'] = $validations;
            $this->addJavaScript('validation');
        }
        return $deps;
    }

    protected function checkDependencies($fieldSet)
    {
        if(is_a($fieldSet, 'Fieldset'))
        {
            foreach($fieldSet->getFields() as $field)
            {
                if(is_a($field,'ContentObject'))
                {
                    $fieldSet->checkDependencies($field);
                }
            }
        }
        parent::checkDependencies($fieldSet);
    }

    private function getValidations()
    {
        $validations = $this->getInnerValidations();

        if (!empty($validations)) {
            $this->addJavaScript('validation');
        }

        return $validations;
    }

    private function getInnerValidations()
    {
        $validations = array();

        foreach ($this->formElements as $formElement)
        {
            $validations = array_merge($validations,$formElement->getValidations());
        }

        return $validations;
    }

//    public function addCustomValidation($validation)
//    {
//        array_push($this->customValidations,$validation);
//    }
//    public function getCustomValidations()
//    {
//        return $this->customValidations;
//    }

//    //Can be used to list the $_GET or $_POST variables for use
//    //  in processing this form
//    // Not true....was it at some point?
//    public function listVariables()
//    {
//        $type = '$_'.strtoupper($this->method);
//        foreach ($this->fieldsetArray as $fieldset)
//        {
//            if(is_a($fieldset,"Fieldset"))
//            {
//                echo $fieldset->listVariables($type) . "\n";
//            }
//            else
//            {
//                 echo '$'.$fieldset->name.' = addslashes(
//fixSmartQuotes('.$type."['".$fieldset->name."']));\n";
//            }
//        }
//    }
}