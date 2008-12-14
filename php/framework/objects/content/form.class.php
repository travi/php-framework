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
	var $name;						//form name attribute
	var $method;					//form method attribute (GET or POST)
	var $action;					//form action attribute (script that information is submitted to for processing)
	var $encType;					//form encType attribute (needs to be changed to "multipart/form-data" for file uploads)
	var $fieldsetArray = array();	//Fieldsets contained in this form
	var $currentFieldset;			//Fieldset where new Fields are to be added
	var $customValidations = array();

	function Form($name,$action="",$method="post")
	{
		$this->name = $name;
		$this->method = $method;
		if(!empty($action))
			$this->action = $action;
		else
			$this->action = htmlentities($_SERVER['REQUEST_URI'] . "#Results");

		$this->addStyleSheet('/resources/shared/css/travi.form.css');
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_FORM_ALIGN);
		$this->addJsInit("$('form[name=\"".$this->name."\"]').alignFields();");
	}
	function addFieldset($fieldset)
	{
		array_push($this->fieldsetArray,$fieldset);
		if(is_a($fieldset,"Fieldset"))
			$this->currentFieldset = (count($this->fieldsetArray) - 1);
	}
	//empties the currentFieldSet variable
	//   so that the next fields will be added outside of a fieldset
	function closeFieldset()
	{
		unset($this->currentFieldset);
	}
	function addField($field)
	{
		if(!isset($this->currentFieldset))
			$this->AddFieldset($field);
		else
		{
			$this->fieldsetArray[$this->currentFieldset]->AddField($field);
		}
	}
	function contains($type)
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
	function listVariables()
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
	function getValidations()
	{
		$validations = array();

		foreach ($this->fieldsetArray as $fieldset)
		{
			$validations = array_merge($validations,$fieldset->getValidations());
		}

		return $validations;
	}
	function addCustomValidation($validation)
	{
		array_push($this->customValidations,$validation);
	}
	function getCustomValidations()
	{
		return $this->customValidations;
	}
	function __toString()
	{
		$form = '
		<form name="'.$this->name.'" method="'.$this->method.'" action="'.$this->action.'"';
		if($this->contains("FileInput"))
		{
			$this->encType = "multipart/form-data";
			$form .= ' enctype="'.$this->encType.'"';
		}
		$form .= '>';
		foreach ($this->fieldsetArray as $fieldset)
		{
			$form .= $fieldset;
			$this->checkDependencies($fieldset);
		}
		$form .= '
		</form>';

		$validations = $this->getValidations();
		$customValidations = $this->getCustomValidations();

		//Currently assumes that this is the only form on the page.
		//To validate more than one form on a single page, use a unique name for each 'frmvalidator'
		if(!empty($validations) || !empty($customValidations))
		{
			$this->addJavaScript('/reusable/js/gen_validatorv2.js');

			$form .= '
		<script language="JavaScript" type="text/javascript">
 		var frmvalidator = new Validator("'.$this->name.'");';

	 		foreach($validations as $validation)
	 		{
 				$form .= '
			frmvalidator.addValidation('.$validation.')';
 			}
 			foreach($customValidations as $validation)
	 		{
 				$form .= '
			frmvalidator.setAddnlValidationFunction('.$validation.')';
 			}

 			$form .= '
		</script>';
		}

		return $form;
	}
}

//////////////////////////////////////////////////////////////////////
//								Fieldset							//
//////////////////////////////////////////////////////////////////////

class Fieldset extends contentObject
{
	var $legend;				//test that appears in the legend for this fieldset
	var $fieldArray = array();	//Fields contained in this fieldset

	function Fieldset($legend)
	{
		$this->legend = $legend;
	}
	function addField($field)
	{
		array_push($this->fieldArray,$field);
	}
	function getValidations()
	{
		$validations = array();
		foreach ($this->fieldArray as $field)
		{
			$validations = array_merge($validations,$field->getValidations());
		}
		return $validations;
	}
	function contains($type)
	{
		foreach ($this->fieldArray as $field)
		{
			if(is_a($field,$type))
				return true;
		}
		return false;
	}
	function listVariables($method)
	{
		$list = "";
		foreach ($this->fieldArray as $field)
		{
			 $list .= '$'.$field->name.' = addslashes(fixSmartQuotes('.$method."['".$field->name."']));\n";
		}
		return $list;
	}
	function __toString()
	{
		$form = '
			<fieldset>
				<legend>'.$this->legend.'</legend>
				<ul class="fieldList">';
		foreach ($this->fieldArray as $field)
		{
			$form .= '<li>'.$field.'</li>';
			if(is_a($field,'ContentObject'))
				$this->checkDependencies($field);
		}
		$form .= '
				</ul>
			</fieldset>';

		return $form;
	}
}

//////////////////////////////////////////////////////////////////////
//						 	Field Interface							//
//////////////////////////////////////////////////////////////////////

interface Field //extends ContentObject//infterface
{
	public function getValidations();
	public function addValidation($validation);
}

//////////////////////////////////////////////////////////////////////
//						 Single Input Fields						//
//////////////////////////////////////////////////////////////////////

abstract class Input extends ContentObject implements Field
{
	protected $label;					//label associated with this field
	protected $name;					//name attribute for this field
	protected $validations = array();	//list of validations that are to be applied to this field at submission time
	
	protected $type;					//type attribute for this field
	protected $value;					//value attribute for this field
	protected $class;					//class attribute for this field

	function Input($label,$value,$name)
	{
		$this->label = $label;
		if(!empty($name))
			$this->name = $name;
		else
		{
			$this->name = str_replace(' ','_',strtolower($label));
			//ensure value is not "name" or ...
			if($this->name == 'name')
				$this->name .= '_value';
		}
		$this->value = $value;
	}
	function addValidation($validation)
	{
		array_push($this->validations,$validation);
	}
	function getValidations()
	{
		$validations = array();
		foreach($this->validations as $validation)
		{
			$check = '"'.$this->name.'","'.$validation.'"';
			array_push($validations,$check);
		}
		return $validations;
	}
	function __toString()
	{
		$form = '
				<label for="'.$this->name.'">'.$this->label.'</label>
				<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.'" class="'.$this->class.'"/>';
		return $form;
	}
}
class TextInput extends Input
{
	function TextInput($label,$value="",$name="")
	{
		parent::Input($label,$value,$name);
		$this->class = "textInput";
		$this->type = "text";
	}
}
class PasswordInput extends Input
{
	function PasswordInput($label,$value="",$name="")
	{
		parent::Input($label,$value,$name);
		$this->class = "textInput";
		$this->type = "password";
	}
}
class FileInput extends Input
{
	function FileInput($label,$value="",$name="")
	{
		parent::Input($label,$value,$name);
		$this->class = "fileInput";
		$this->type = "file";
	}
}
class UrlInput extends Input
{
	function UrlInput($label,$value="",$name="")
	{
		parent::Input($label,$value,$name);
		$this->class = "textInput";
		$this->type = "text";
	}
	function __toString()
	{
		$form = parent::toString();
		$form .= ' ';

		$preview = new PreviewWindow();
		$preview->setLinkText('Preview');
		$preview->setField($this->name);

		$this->checkDependencies($preview);

		$form .= $preview->toString();

		return $form;
	}
}
class HiddenInput extends Input
{
	function HiddenInput($name,$value="")
	{
		parent::Input("",$value,$name);
		$this->type = "hidden";
	}
	function __toString()
	{
		return '
				<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.'"/>';
	}
}

class DateInput extends Input
{
	function DateInput($label,$value="",$name="")
	{
		parent::Input($label,$value,$name);
		$this->type = "text";
		$this->class = "textInput datepicker";
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_UI);
		$this->addJsInit("$('input.datepicker').datepicker({ dateFormat:'yy-mm-dd',"
			."buttonImage:'/resources/shared/img/calendar.gif',"
			."buttonImageOnly: true, showOn: 'both' });");
	}
}
class TimeInput extends Input
{
	function TimeInput($label,$value="",$name="")
	{
		parent::Input($label,$value,$name);
		$this->type = "text";
		$this->class = "textInput";
		$this->addJavaScript('/reusable/js/time.js');
	}
	function __toString()
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
	function __toString()
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
	var $class;
	var $rows;

	function TextArea($label,$value="",$name="",$rows=3)
	{
		parent::Input($label,$value,$name);
		$this->class = "textInput";
		$this->rows = $rows;
	}
	function __toString()
	{
		return '
				<label for="'.$this->name.'">'.$this->label.'</label>
				<textarea name="'.$this->name.'" id="'.$this->name.'" rows="'.$this->rows.'" class="'.$this->class.'">'
					.$this->value.
				'</textarea>';
	}
}
class RichTextArea extends TextArea
{
	function RichTextArea($label,$value="",$name="",$rows=3)
	{
		parent::TextArea($label,$value,$name);
		$this->class = "textInput richEditor";
		$this->rows = $rows;
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_WYMEDITOR);
		$this->addJsInit("$('textarea.richEditor').wymeditor({skin:'silver',updateSelector:'#Submit'});");
	}
	function __toString()
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
	var $confirmation;

	function SubmitButton($value,$class="submitButton")
	{
		$this->type = "submit";
		$this->name = "Submit";
		$this->class = $class;
		$this->value = $value;
	}
	function setConfirmation($confirmation)
	{
		$this->confirmation = $confirmation;
	}
	function __toString()
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
	var $type;
	var $class;
	var $value;
	var $name;

	function Button($value,$class="button")
	{
		$this->type = "submit";
		$this->name = "Submit";
		$this->class = $class;
		$this->value = $value;
	}
	
	function getValidations()
	{
		return array();
	}

	function __toString()
	{
		$string = '
				<button type="'.$this->type.'" class="'.$this->class.'">'.$this->value.'</button>';
	
		return $string;
	}
}

class NoteArea
{
	var $label;
	var $content;

	function NoteArea($label,$content)
	{
		$this->label = $label;
		$this->content = $content;
	}
	function getValidations()
	{
		return array();
	}
	function __toString()
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

class Choices //abstract
{
	var $label;
	var $name;
	var $settings = array();
	var $options = array(); 	//implemented as an n x 4 two-dimensional array

	function Choices($settings=array())
	{
		$this->label = $settings['label'];
		if(!empty($settings['name']))
			$this->name = $settings['name'];
		else
			$this->name = strtolower($settings['label']);
		$this->settings = $settings;
	}

	function addOption($option,$selected=false,$value="",$disabled=false)
	{
		$optionAR = array($option,$value,$selected,$disabled);
		array_push($this->options,$optionAR);
	}
	function getValidations()
	{
		return array();
	}
	function __toString()
	{
		$form = '
				<fieldset>
					<legend>'.$this->label.'</legend>';
					
		foreach ($this->options as $option)
		{				
			$form .= '
					<label>
						<input type="'.$this->type.'" name="'.$this->name.'" value="';
			if(!empty($option[1]))
				$form .= $option[1];
			else
				$form .= $option[0];
			$form .= '" class="'.$this->class.'"';
			if($option[3])
			{	
				$form .= ' disabled';
			}
			if($option[2]||($option[0]==$this->settings['value'])||($option[1]==$this->settings['value']))
				$form .= ' checked ';
			$form .= '/>'.$option[0].'
					</label>';
		}
				
		$form .= '
				</fieldset>';
					
		return $form;
	}
}

class SelectionBox extends Choices
{
	function SelectionBox($options=array())
	{
		parent::Choices($options);
		$this->addOption("Select One");
	}
	function __toString()
	{
		$form = '
				<label for="'.$this->name.'">'.$this->label.'</label>
 				<select name="'.$this->name.'" id="'.$this->name.'" class="textInput">';
			foreach ($this->options as $option)
			{
				$form .= '
					<option';
				if(!empty($option[1]))
				{
					$form .= ' value="'.$option[1].'"';
				}
				if($option[3])
				{	$form .= ' disabled';
				}
				if($option[2]||(!empty($this->settings['value'])&&(($option[0]==$this->settings['value'])||($option[1]==$this->settings['value']))))
					$form .= ' selected';
				$form .= '>'.$option[0].'</option>';
			}
		$form .= '
				</select>';
		return $form;
	}
}
class RadioButtons extends Choices
{
	var $type;
	var $class;

	function RadioButtons($options=array())
	{
		parent::Choices($options);
		$this->type = "radio";
		$this->class = "radioButton";
	}
}
class CheckBoxes extends Choices
{
	var $type;
	var $class;

	function CheckBoxes($options=array())
	{
		parent::Choices($options);
		$this->type = "checkbox";
		$this->class = "checkbox";
	}
}
?>
