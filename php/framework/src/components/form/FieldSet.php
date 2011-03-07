<?php

require_once(dirname(__FILE__).'/../../../objects/content/contentObject.class.php');

class FieldSet extends contentObject
{
	private $legend;                //text that appears in the legend for this fieldSet
	private $fieldArray = array();	//Fields contained in this fieldSet

	public function __construct($options)
	{
		$this->legend = $options['legend'];
		foreach ($options['fields'] as $field) {
			$this->addField(new $field['type']($field));
		}
	}
    public function getLegend()
    {
        return $this->legend;
    }
	public function addField($field)
	{
		array_push($this->fieldArray,$field);
	}
    public function getFields()
    {
        return $this->fieldArray;
    }
	public function getValidations()
	{
		$validations = array();
		foreach ($this->fieldArray as $field)
		{
			if (is_a($field,'Input') || is_a($field,'Choices')) {
				$validations[$field->getName()] = $field->getValidations();
			}
		}
		return $validations;
	}
	public function contains($type)
	{
		foreach ($this->fieldArray as $field)
		{
			if(is_a($field,$type))
				return true;
		}
		return false;
	}
//	public function listVariables($method)
//	{
//		$list = "";
//		foreach ($this->fieldArray as $field)
//		{
//			 $list .= '$'.$field->name.' = addslashes(fixSmartQuotes('.$method."['".$field->name."']));\n";
//		}
//		return $list;
//	}
    public function getDependencies()
    {
		foreach ($this->fieldArray as $field)
		{
			$this->checkDependencies($field);
		}
    }
}