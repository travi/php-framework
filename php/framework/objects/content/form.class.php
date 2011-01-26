<?php
/*
 * Created on Dec 22, 2005
 * By Matt Travi
 */

require_once(dirname(__FILE__).'/../../src/components/form/Form.php');
require_once(dirname(__FILE__).'/../../src/components/form/FieldSet.php');
require_once(dirname(__FILE__).'/../../src/components/form/Field.php');
require_once(dirname(__FILE__).'/../../src/components/form/Input.php');
require_once(dirname(__FILE__).'/../../src/components/form/TextInput.php');
require_once(dirname(__FILE__).'/../../src/components/form/FileInput.php');
require_once(dirname(__FILE__).'/../../src/components/form/PasswordInput.php');

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
class HiddenInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->type = "hidden";
        $this->setTemplate('components/form/input.tpl');
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
		$this->addJsInit("$('input.datepicker').datepicker({
		                                            dateFormat:'yy-mm-dd',
		                                            buttonImage:'/resources/shared/img/calendar.gif',
		                                            buttonImageOnly: true, showOn: 'both'
                                                });");
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
        $this->setTemplate('components/form/textArea.tpl');
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
        $this->addJavaScript('wymEditor-fullScreen');
		$this->addJsInit("$('textarea.richEditor').wymeditor({
                                                        skin:'silver',
                                                        updateSelector:'#Submit',
                                                        postInit: function(wym){
                                                            wym.fullscreen();
                                                        }
                                                    });");
        $this->setTemplate('components/form/richTextArea.tpl');
	}
}
class SubmitButton extends Input
{
	protected $confirmation;

	public function __construct($options)
	{
		parent::__construct($options);
        $this->label = "";
		$this->type = "submit";
		$this->name = "Submit";
		if(!empty($options['class']))
			$this->class = $options['class'];
		else
			$this->class = "submitButton";
		$this->value = $options['label'];
        $this->setTemplate('components/form/input.tpl');
        $this->addJavaScript('jqueryUi');
        $this->addJsInit('$("input[type=submit]").button()');
	}
	//TODO need to replace this technique using UI dialog
	public function setConfirmation($confirmation)
	{
		$this->confirmation = $confirmation;
	}
//	public function __toString()
//	{
//		$string = '
//						<input type="'.$this->type.'" name="'.$this->name.'" id="'.$this->name.'" value="'.$this->value.
//					'" class="'.$this->class.'"';
//		if(!empty($this->confirmation))
//			$string .= ' onclick="if (confirm(\''.$this->confirmation.'\')) return true; else return false;"';
//		$string .= '/>';
//
//		return $string;
//	}
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

class NoteArea extends ContentObject
{
	private $label;
	private $content;

	public function __construct($options)
	{
		$this->label = $options['label'];
		$this->content = $options['content'];
        $this->setTemplate('components/form/noteArea.tpl');
	}
    public function getLabel()
    {
        return $this->label;
    }
    public function getContent()
    {
        return $this->content;
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
    protected $template;                //template file to be used when rendering
	
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
        $this->setTemplate('components/form/choices.tpl');
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
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    public function getTemplate()
    {
        return $this->template;
    }
}

class SelectionBox extends Choices
{
	private $optGroups = array();
	
	public function __construct($options=array())
	{
		$this->addOption("Select One");
		parent::__construct($options);
        $this->setTemplate('components/form/selectionBox.tpl');
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
