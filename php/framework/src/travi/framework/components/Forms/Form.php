<?php

namespace travi\framework\components\Forms;

use travi\framework\components\Forms\FormElementGroup,
    travi\framework\components\Forms\inputs\Input,
    travi\framework\components\Forms\FieldSet;
use travi\framework\DependantObject;

class Form extends FormElementGroup
{
    const FORMS_NAMESPACE = "travi\\framework\\components\\Forms\\";
    /** @var string */
    private $name;
    /** @var string */
    private $method;
    /** @var string */
    private $action;
    /** @var string */
    private $encodingType;
    /** @var boolean */
    private $debug;

    private $actions = array();
    public $key;

    public function __construct($options)
    {
        if (isset($options['name'])) {
            $this->name = $options['name'];
        }
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

        $this->addStyleSheet('/resources/thirdparty/travi-styles/css/travi-form.css');
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
        if ($this->containsFormElementType(self::FORMS_NAMESPACE . 'inputs\\FileInput')) {
            $this->encodingType = "multipart/form-data";
        }
        return $this->encodingType;
    }

    public function getDependencies()
    {
        foreach ($this->actions as $action) {
            /** @var DependantObject $action */
            $this->addDependencies($action->getDependencies());
        }

        $validations = $this->getValidations();

        $dependencies = parent::getDependencies();

        if (!empty($validations)) {
            $dependencies['validations'] = $validations;
        }


        return $dependencies;
    }

    public function getValidations()
    {
        $validations = parent::getValidations();

        if (!empty($validations)) {
            $this->addJavaScript('validation');
        }

        return $validations;
    }

    /**
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function mapErrorMessagesToFields($errors)
    {
        foreach ($errors as $fieldName => $error) {
            if ('name' === $fieldName) {
                $fieldName = $fieldName . '_value';
            }
            $this->getFieldByName($fieldName)->setValidationError($error);
        }
    }

    public function hasErrors()
    {
        return !$this->isValid();
    }

    public function addAction($action)
    {
        array_push($this->actions, $action);
    }

    public function getActions()
    {
        return $this->actions;
    }
}