<?php

require_once(dirname(__FILE__).'/FormElementGroup.php');

class Form extends FormElementGroup
{
    private $name;
    private $method;
    private $action;
    private $encodingType;
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
        if ($this->containsFormElementType("FileInput")) {
            $this->encodingType = "multipart/form-data";
        }
        return $this->encodingType;
    }

    public function getDependencies()
    {
        $validations = $this->getValidations();
        $deps = parent::getDependencies();
        if (!empty($validations)) {
            $deps['validations'] = $validations;
        }
        return $deps;
    }

    public function getValidations()
    {
        $validations = parent::getValidations();

        if (!empty($validations)) {
            $this->addJavaScript('validation');
        }

        return $validations;
    }
}