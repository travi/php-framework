<?php

namespace Travi\framework\components\Forms;

use Travi\framework\components\Forms\FormElementGroup,
    Travi\framework\components\Forms\inputs\Input,
    Travi\framework\components\Forms\FieldSet;

class Form extends FormElementGroup
{
    const FORMS_NAMESPACE = "Travi\\framework\\components\\Forms\\";
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

    public function __construct($options)
    {
        if (isset($options['debug'])) {
            $this->debug = $options['debug'];
        }
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

        if (!empty($options['fieldsets'])) {
            foreach ($options['fieldsets'] as $formElement) {
                if (!empty($formElement['fields'])) {
                    $this->addFormElement(new Fieldset($formElement));
                } else if (!empty($formElement['type'])) {
                    $type = $formElement['type'];
                    $this->addFormElement(
                        new $type($formElement)
                    );
                }
            }
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
}