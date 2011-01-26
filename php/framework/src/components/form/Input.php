<?php

require_once(dirname(__FILE__).'/Field.php');

abstract class Input extends ContentObject implements Field
{
	protected $label;					//label associated with this field
	protected $name;					//name attribute for this field
	protected $validations = array();	//list of validations
	protected $type;					//type attribute for this field
	protected $value;					//value attribute for this field
	protected $class;					//class attribute for this field

	public function __construct($options)
	{
		$this->label = $options['label'];
		if(!empty($options['name']))
			$this->name = $options['name'];
		else
		{
			$this->name = str_replace(' ','_',strtolower($options['label']));
			//ensure value is not "name" or ... (expandos)
			if($this->name == 'name')
				$this->name .= '_value';
		}
		$this->value = $options['value'];
		if(!empty($options['validations']))
		{
			foreach($options['validations'] as $validation)
			{
				$this->addValidation($validation);
			}
		}
        $this->setTemplate('components/form/inputWithLabel.tpl');
	}
	public function addValidation($validation)
	{
		array_push($this->validations,$validation);
	}
	public function getName()
	{
		return $this->name;
	}
    public function getLabel()
    {
        return $this->label;
    }
    public function getType()
    {
        return $this->type;
    }
    public function getValue()
    {
        return $this->value;
    }
    public function getClass()
    {
        return $this->class;
    }
	public function getValidations()
	{
		return $this->validations;
	}
}