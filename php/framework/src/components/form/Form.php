<?php

require_once(dirname(__FILE__).'/../../../objects/content/contentObject.class.php');

class Form extends ContentObject
{
	private $name;                          //form name attribute
	private $method;						//form method attribute (GET or POST)
	private $action;						//form action attribute (script that information is submitted to for processing)
	private $encodingType;                  //form encType attribute (needs to be changed to "multipart/form-data" for file uploads)
	private $fieldsetArray = array();		//FieldSets contained in this form
	private $currentFieldset;				//FieldSet where new Fields are to be added
	private $debug;

	public function __construct($options)
	{
		$this->debug = $options['debug'];
		$this->name = $options['name'];
		if(!empty($options['method']))
			$this->setMethod($options['method']);
		else
			$this->method = 'post';
		if(!empty($options['action']))
			$this->action = $options['action'];
		else
			$this->action = htmlentities($_SERVER['REQUEST_URI'] . "#Results");

		if(!empty($options['fieldsets']))
		{
			foreach($options['fieldsets'] as $fieldset)
			{
				if(!empty($fieldset['fields']))
					$this->addFieldset(new Fieldset($fieldset));
				else if(!empty($fieldset['type']))
				{
					$this->closeFieldset();
					$this->addFieldset(new $fieldset['type']($fieldset));
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

    public function getFieldsets()
    {
        return $this->fieldsetArray;
    }

	public function addFieldset($fieldset)
	{
		array_push($this->fieldsetArray,$fieldset);
		if(is_a($fieldset,"Fieldset"))
			$this->currentFieldset = (count($this->fieldsetArray) - 1);
	}

	//empties the currentFieldSet variable
	//   so that the next fields will be added outside of a fieldset
	public function closeFieldset()
	{
		unset($this->currentFieldset);
	}

	public function addField($field)
	{
		if(!isset($this->currentFieldset))
			$this->addFieldset($field);
		else
		{
			$this->fieldsetArray[$this->currentFieldset]->AddField($field);
		}
	}

	public function contains($type)
	{
		foreach ($this->fieldsetArray as $fieldset)
		{
			if(is_a($fieldset,"Fieldset"))
			{
				if($fieldset->contains($type))
					return true;
			}
		}
		return false;
	}

//	//Can be used to list the $_GET or $_POST variables for use
//	//  in processing this form
//  // Not true....was it at some point?
//	public function listVariables()
//	{
//		$type = '$_'.strtoupper($this->method);
//		foreach ($this->fieldsetArray as $fieldset)
//		{
//			if(is_a($fieldset,"Fieldset"))
//			{
//				echo $fieldset->listVariables($type) . "\n";
//			}
//			else
//			{
//				 echo '$'.$fieldset->name.' = addslashes(fixSmartQuotes('.$type."['".$fieldset->name."']));\n";
//			}
//		}
//	}

	public function getInnerValidations()
	{
		$validations = array();

		foreach ($this->fieldsetArray as $fieldset)
		{
			$validations = array_merge($validations,$fieldset->getValidations());
		}

		return $validations;
	}

//	public function addCustomValidation($validation)
//	{
//		array_push($this->customValidations,$validation);
//	}
//	public function getCustomValidations()
//	{
//		return $this->customValidations;
//	}

	private function buildValidationInit($validations)
	{
		$valInit = "$('form[name=\"".$this->name."\"]').validate({";

		if($this->debug)
			$valInit .= "
					debug: true,";

		$valInit .= "
					errorClass: 'ui-state-error',";

		$valInit .= "
					rules: {";

		foreach($validations as $field => $vals)
		{
			if(!empty($vals))
			{
				if($i > 0)
					$valInit .= ",";

				$valInit .= "
						".$field.": ";

				//TODO: need to find a good way of conditionally adding commas between rules

				if(sizeof($vals) == 1 && $vals[0] == 'required')
					$valInit .= '"required"';
				elseif(sizeof($vals) == 1)
					$valInit .= '"required"'; //should this ever happen?
				else
				{
					$valInit .= "{
							required: true,";

					if(in_array('email',$vals))
						$valInit .= '
							email: true';

					$valInit .= "
						}";
				}

				$i++;
			}
		}

		$valInit .= "
					}";

		$valInit .= "
				});";

		return $valInit;
	}

    public function getDependencies()
    {
        $this->getValidations();
		foreach ($this->fieldsetArray as $fieldset)
		{
			$this->checkDependencies($fieldset);
		}
        return parent::getDependencies();
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

		if(!empty($validations))
		{
			$this->addJavaScript('validation');
			$this->addJsInit($this->buildValidationInit($validations));
		}
    }
}