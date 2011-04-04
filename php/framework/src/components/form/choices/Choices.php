<?php
/**
 * Created on Feb 13, 2011
 * By Matt Travi
 * programmer@travi.org
 */

require_once(dirname(__FILE__).'/../Field.php');

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

    protected function optionAdder($options=array())
    {
        foreach ($options as $option) {
            if (is_array($option)) {
                if (isset($this->value) && $this->value == $option['value']) {
                    $selected = true;
                } elseif ($option['selected']) {
                    $selected = true;
                } else {
                    $selected = false;
                }

                $this->addOption($option['label'], $option['value'], $selected);
            } else {
                $this->addOption($option);
            }
        }
    }

    public function addOption($option,$value="", $selected=false, $disabled=false)
    {
        $optionAR = array(  'option'    => $option,
                            'value'     => $value,
                            'selected'  => $selected,
                            'disabled'  => $disabled);

        array_push($this->options, $optionAR);
    }

    public function getName()
    {
        return $this->name;
    }
    public function getLabel()
    {
        return $this->label;
    }
    public function getOptions()
    {
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
}

 
