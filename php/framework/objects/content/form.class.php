<?php
/*
 * Created on Dec 22, 2005
 * By Matt Travi
 */

require_once('contentObject.class.php');

//////////////////////////////////////////////////////////////////////
//						 		Form								//
//////////////////////////////////////////////////////////////////////

class Form extends ContentObject
{
	protected $name;						//form name attribute
	protected $method;						//form method attribute (GET or POST)
	protected $action;						//form action attribute (script that information is submitted to for processing)
	protected $encType;						//form encType attribute (needs to be changed to "multipart/form-data" for file uploads)
	protected $fieldsetArray = array();		//Fieldsets contained in this form
	protected $currentFieldset;				//Fieldset where new Fields are to be added
	protected $debug;
	protected $customValidations = array();

	public function __construct($options)
	{
		$this->debug = $options['debug'];
		$this->name = $options['name'];
		if(!empty($options['method']))
			$this->method = $options['method'];
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
    public function getAction()
    {
        return $this->action;
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
			$this->AddFieldset($field);
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

	//Can be used to list the $_GET or $_POST variables for use
	//  in processing this form
	public function listVariables()
	{
		$type = '$_'.strtoupper($this->method);
		foreach ($this->fieldsetArray as $fieldset)
		{
			if(is_a($fieldset,"Fieldset"))
			{
				echo $fieldset->listVariables($type) . "\n";
			}
			else
			{
				 echo '$'.$fieldset->name.' = addslashes(fixSmartQuotes('.$type."['".$fieldset->name."']));\n";
			}
		}
	}
	public function getInnerValidations()
	{
		$validations = array();

		foreach ($this->fieldsetArray as $fieldset)
		{
			$validations = array_merge($validations,$fieldset->getValidations());
		}

		return $validations;
	}
	public function addCustomValidation($validation)
	{
		array_push($this->customValidations,$validation);
	}
	public function getCustomValidations()
	{
		return $this->customValidations;
	}
	public function buildValidationInit($validations)
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
        return array(   'scripts'   => $this->scripts,
                        'jsInits'   => $this->jsInits,
                        'styles'    => $this->styles);
    }
    public function getValidations()
    {
		$validations = $this->getInnerValidations();
		$customValidations = $this->getCustomValidations();

		if(!empty($validations) || !empty($customValidations))
		{
			$this->addJavaScript('validation');
			$this->addJsInit($this->buildValidationInit($validations));
		}
    }
}

//////////////////////////////////////////////////////////////////////
//								Fieldset							//
//////////////////////////////////////////////////////////////////////

class Fieldset extends contentObject
{
	protected $legend;					//text that appears in the legend for this fieldset
	protected $fieldArray = array();	//Fields contained in this fieldset

	public function __construct($options)
	{
		$this->legend = $options['legend'];
		foreach($options['fields'] as $field)
		{
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
			if(is_a($field,'Input') || is_a($field,'Choices'))
			{
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
	public function listVariables($method)
	{
		$list = "";
		foreach ($this->fieldArray as $field)
		{
			 $list .= '$'.$field->name.' = addslashes(fixSmartQuotes('.$method."['".$field->name."']));\n";
		}
		return $list;
	}
    public function getDependencies()
    {
		foreach ($this->fieldArray as $field)
		{
			$this->checkDependencies($field);
		}
    }
}

//////////////////////////////////////////////////////////////////////
//						 	Field Interface							//
//////////////////////////////////////////////////////////////////////

interface Field
{
	public function getValidations();
	public function getName();
	public function addValidation($validation);
}

//////////////////////////////////////////////////////////////////////
//						 Single Input Fields						//
//////////////////////////////////////////////////////////////////////

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
			//ensure value is not "name" or ...
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
class TextInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "textInput";
		$this->type = "text";
	}
}
class PasswordInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "textInput";
		$this->type = "password";
	}
}
class FileInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "fileInput";
		$this->type = "file";
	}
}
class UrlInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "textInput";
		$this->type = "text";
	}
	public function __toString()
	{
		$form = parent::__toString();
		$form .= ' ';

		$preview = new PreviewWindow();
		$preview->setLinkText('Preview');
		$preview->setField($this->name);

		$this->checkDependencies($preview);

		$form .= $preview->__toString();

		return $form;
	}
}
class HiddenInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->type = "hidden";
	}
	public function __toString()
	{
		return '
						<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.'"/>';
	}
}

class DateInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->type = "text";
		$this->class = "textInput datepicker";
		$this->addJavaScript('jqueryUi');
		$this->addJsInit("$('input.datepicker').datepicker({ dateFormat:'yy-mm-dd',"
			."buttonImage:'/resources/shared/img/calendar.gif',"
			."buttonImageOnly: true, showOn: 'both' });");
	}
}
class TimeInput extends Input
{
	public function __construct($options)
	{
		parent::Input($options);
		$this->type = "text";
		$this->class = "textInput";
		$this->addJavaScript('/reusable/js/time.js');
	}
	public function __toString()
	{
		$hour = substr($this->value,0,2);
		if($hour >= 12)
		{
			$ampm = 'pm';
			if ($hour > 12)
				$hour -= 12;
		}
		else $ampm = 'am';
		$minute = substr($this->value,3,2);

		$form = '
				<label for="'.$this->name.'">'.$this->label.'</label>
 				<select name="'.$this->name.'_hour" id="'.$this->name.'_hour" onchange="javascript:updateHiddenField('."'".$this->name."'".')" class="timeInput hour">';
		for ($i = 1; $i <= 12; $i++)
		{
			$form .= '
					<option';
			if($hour == $i)
				$form .= ' selected';
			$form .= '>'.$i.'</option>';
		}
		$form .= '
				</select>
				<select name="'.$this->name.'_minute" id="'.$this->name.'_minute" onchange="javascript:updateHiddenField('."'".$this->name."'".')" class="timeInput minute">';
		for ($i = 0; $i <= 60; $i += 5)
		{
			if(strlen($i) == 1)
				$i = "0".$i;
			$form .= '
					<option';
			if($minute == $i)
				$form .= ' selected';
			$form .= '>'.$i.'</option>';
		}
		$form .= '
				</select>
				<select name="'.$this->name.'_ampm" id="'.$this->name.'_ampm" onchange="javascript:updateHiddenField('."'".$this->name."'".')" class="timeInput ampm">';
		$form .= '
					<option';
			if($ampm == 'am')
				$form .= ' selected';
			$form .= '>am</option>
					<option';
			if($ampm == 'pm')
				$form .= ' selected';
			$form .= '>pm</option>
				</select>';
		$hidden = new HiddenInput($this->name,$this->value);
		$form .= $hidden->toString();
		$form .= '
				<br />';
		return $form;
	}
}
class CityStateZip
{
	public function __toString()
	{
		return '
				<label for="city">City</label>
				<input type="text" name="city" id="city" value="'.$this->city.'" class="textInput city"/>
				<label class="inlineLabel" for="state">State</label>
				<input type="text" name="state" id="state" value="'.$this->state.'" class="textInput state" size="2" maxlength="2"/>
				<label class="inlineLabel" for="zip">Zip</label>
				<input type="text" name="zip" id="zip" value="'.$this->zip.'" class="textInput zip" size="5" maxlength="5"/>
				<br />';
	}
}
class TextArea extends Input
{
	protected $class;
	protected $rows;

	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "textInput";
		$this->rows = $options['rows'];
	}
	public function getRows()
    {
        return $this->rows;
    }
}
class RichTextArea extends TextArea
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "textInput richEditor";
		$this->addJavaScript('wymEditor');
		$this->addJsInit("$('textarea.richEditor').wymeditor({skin:'silver',updateSelector:'#Submit'});");
	}
	public function __toString()
	{
		return '
						<label for="'.$this->name.'">'.$this->label.'</label>
						<div class="formBlock">
							<textarea name="'.$this->name.'" id="'.$this->name.'" class="'.$this->class.'">
								'.htmlentities($this->value).'
							</textarea>
						</div>';
	}
}
class SubmitButton extends Input
{
//    TODO: use jQuery UI button styling

	protected $confirmation;

	public function __construct($options)
	{
		$this->type = "submit";
		$this->name = "Submit";
		if(!empty($options['class']))
			$this->class = $options['class'];
		else
			$this->class = "submitButton";
		$this->value = $options['label'];
	}
	//TODO need to replace this technique using UI dialog
	public function setConfirmation($confirmation)
	{
		$this->confirmation = $confirmation;
	}
	public function __toString()
	{
		$string = '
						<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.
					'" class="'.$this->class.'"';
		if(!empty($this->confirmation))
			$string .= ' onclick="if (confirm(\''.$this->confirmation.'\')) return true; else return false;"';
		$string .= '/>';

		return $string;
	}
}

class Button
{
//    TODO: use jQuery UI button styling

	protected $type;
	protected $class;
	protected $value;
	protected $name;

	public function __construct($value,$class="button")
	{
		$this->type = "submit";
		$this->name = "Submit";
		$this->class = $class;
		$this->value = $value;
	}
	
	public function getValidations()
	{
		return array();
	}

	public function __toString()
	{
		$string = '
				<button type="'.$this->type.'" class="'.$this->class.'">'.$this->value.'</button>';
	
		return $string;
	}
}

class NoteArea
{
	protected $label;
	protected $content;

	public function __construct($options)
	{
		$this->label = $options['label'];
		$this->content = $options['content'];
	}
	public function getValidations()
	{
		return array();
	}
	public function __toString()
	{
		return '
			<label>'.$this->label.'</label>
			<div class="formBlock notearea">
				'.$this->content.'
			</div>';
	}
}

//////////////////////////////////////////////////////////////////////
//					Fields Giving Numerous Choices					//
//////////////////////////////////////////////////////////////////////

abstract class Choices implements Field
{
	protected $label;					//label associated with this field
	protected $name;					//name attribute for this field
	protected $validations = array();	//list of validations	
	protected $type;					//type attribute for this field
	protected $value;					//value attribute for this field
	protected $class;					//class attribute for this field
	
	protected $settings = array();
	protected $options = array(); 	//implemented as an n x 4 two-dimensional array

	public function __construct($settings=array())
	{
		$this->label = $settings['label'];
		if(!empty($settings['name']))
			$this->name = $settings['name'];
		else
			$this->name = strtolower($settings['label']);
		$this->value = $settings['value'];
		$this->settings = $settings;
		$this->optionAdder($settings['options']);
		if(!empty($settings['validations']))
		{
			foreach($settings['validations'] as $validation)
			{
				$this->addValidation($validation);
			}
		}
	}
	
	protected function optionAdder($options=array())
	{
		foreach($options as $option)
		{
			if(is_array($option))
			{
				if(isset($this->value) && $this->value == $option['value'])
					$selected = true;
				elseif($option['selected'])
					$selected = true;
				else
					$selected = false;
					
				$this->addOption($option['label'],$option['value'],$selected);
			}
			else
				$this->addOption($option);
		}
	}

	public function addOption($option,$value="",$selected=false,$disabled=false)
	{
		$optionAR = array(	'option'		=> $option,
							'value'		=> $value,
							'selected'	=> $selected,
							'disabled'	=> $disabled);
							
		array_push($this->options,$optionAR);
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
		array_push($this->validations,$validation);
	}
}

class SelectionBox extends Choices
{
	private $optGroups = array();
	
	public function __construct($options=array())
	{
		$this->addOption("Select One");
		parent::__construct($options);
	}
	protected function optionAdder($options=array())
	{
		if($this->settings['optGroups'])
		{
			foreach($options as $optGroup => $values)
			{				
				$this->optGroups[$optGroup] = array();
				foreach($values as $value)
				{
					$selected=false;
					$disabled=false;
				
					$optionAR = array(	option		=> $value['label'],
										value		=> $value['value'],
										selected	=> $selected,
										disabled	=> $disabled);
										
					array_push($this->optGroups[$optGroup],$optionAR);					
				}
			}
		}
		else
		{
			parent::optionAdder($options);
		}
		
	}
}
class RadioButtons extends Choices
{
	public function __construct($options=array())
	{
		parent::__construct($options);
		$this->type = "radio";
		$this->class = "radioButton";
	}
}
class CheckBoxes extends Choices
{
	public function __construct($options=array())
	{
		parent::__construct($options);
		$this->type = "checkbox";
		$this->class = "checkbox";
	}
}
?>
