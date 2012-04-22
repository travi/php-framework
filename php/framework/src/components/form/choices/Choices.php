<?php
/**
 * Created on Feb 13, 2011
 * By Matt Travi
 * programmer@travi.org
 */

require_once dirname(__FILE__).'/../Field.php';

abstract class Choices extends DependantObject implements Field
{
    protected $label;					//label associated with this field
    protected $name;					//name attribute for this field
    protected $validations = array();	//list of validations
    protected $type;					//type attribute for this field
    protected $value;					//value attribute for this field
    protected $class;					//class attribute for this field
    protected $template;                //template file to be used when rendering

    protected $settings = array();
    protected $options = array();       //implemented as an n x 4 two-dimensional array

    private $error;

    public function __construct($settings=array())
    {
        $this->label = $settings['label'];
        if (!empty($settings['name'])) {
            $this->name = $settings['name'];
        } else {
            $this->name = strtolower($settings['label']);
        }
        $this->value = $settings['value'];
        $this->settings = $settings;
        $this->optionAdder($settings['options']);
        if (!empty($settings['validations'])) {
            foreach ($settings['validations'] as $validation) {
                $this->addValidation($validation);
            }
        }
        $this->setTemplate('components/form/choices.tpl');
    }

    public function addOptions($options) {
        $this->optionAdder($options);
    }

    protected function optionAdder($options=array())
    {
        foreach ($options as $option) {
            if (is_array($option)) {
                $this->addOption(
                    $option['label'],
                    $option['value'],
                    $this->isThisOptionSelected($option)
                );
            } else {
                $this->addOption($option, null, $this->isThisOptionSelected($option));
            }
        }
    }

    private function isThisOptionSelected($option)
    {
        if (isset($this->value) && ($this->value === $option['value']) || ($this->value === $option)) {
            return true;
        } elseif (is_array($option) && $option['selected']) {
            return true;
        } else {
            return false;
        }
    }

    public function addOption($option, $value="", $selected=false, $disabled=false)
    {
        $optionAR = array(
            'option'    => $option,
            'value'     => $value,
            'selected'  => $selected,
            'disabled'  => $disabled
        );

        array_push($this->options, $optionAR);
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
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
    public function getOptions()
    {
        foreach ($this->options as &$option) {
            if ($this->isThisOptionSelected($option)) {
                $option['selected'] = true;
            }
        }

        return $this->options;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getClass()
    {
        return $this->class;
    }
    public function getValidations()
    {
        return $this->validations;
    }
    public function addValidation($validation)
    {
        array_push($this->validations, $validation);
    }
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    public function getTemplate()
    {
        return $this->template;
    }

    public function setValidationError($message)
    {
        $this->error = $message;
    }

    public function getValidationError()
    {
        return $this->error;
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

 
