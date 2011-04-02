<?php
/*
 * Created on Dec 22, 2005
 * By Matt Travi
 */

require_once(dirname(__FILE__) . '/../../src/components/form/Form.php');
require_once(dirname(__FILE__) . '/../../src/components/form/FieldSet.php');
require_once(dirname(__FILE__) . '/../../src/components/form/Field.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/Input.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/TextInput.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/FileInput.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/PasswordInput.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/HiddenInput.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/TextArea.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/RichTextArea.php');
require_once(dirname(__FILE__) . '/../../src/components/form/SubmitButton.php');
require_once(dirname(__FILE__) . '/../../src/components/form/inputs/DateInput.php');
require_once(dirname(__FILE__) . '/../../src/components/form/NoteArea.php');
require_once(dirname(__FILE__) . '/../../src/components/form/choices/Choices.php');
require_once(dirname(__FILE__) . '/../../src/components/form/choices/SelectionBox.php');
require_once(dirname(__FILE__) . '/../../src/components/form/choices/RadioButtons.php');
require_once(dirname(__FILE__) . '/../../src/components/form/choices/CheckBoxes.php');

//////////////////////////////////////////////////////////////////////
//						 		Form								//
//////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////
//								Fieldset							//
//////////////////////////////////////////////////////////////////////



//////////////////////////////////////////////////////////////////////
//						 	Field Interface							//
//////////////////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////////////////
//						 Single Input Fields						//
//////////////////////////////////////////////////////////////////////


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

class Button
{
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
        $this->addJavaScript('jqueryUi');
        $this->addJsInit('$("button").button()');
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
?>
