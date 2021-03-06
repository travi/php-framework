<?php

require_once dirname(__FILE__) . '/../../src/components/Forms/Form.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/FieldSet.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/Field.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/Input.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/TextInput.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/NumberInput.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/FileInput.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/PasswordInput.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/HiddenInput.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/TextArea.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/RichTextArea.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/SubmitButton.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/inputs/DateInput.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/NoteArea.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/choices/Choices.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/choices/SelectionBox.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/choices/RadioButtons.php';
require_once dirname(__FILE__) . '/../../src/components/Forms/choices/CheckBoxes.php';


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
        $preview->setField($this->getName());

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
        $hour = substr($this->getValue(), 0, 2);
        if ($hour >= 12) {
            $ampm = 'pm';
            if ($hour > 12) {
                $hour -= 12;
            }
        } else {
            $ampm = 'am';
        }
        $minute = substr($this->getValue(), 3, 2);

        $form = '
                <label for="'.$this->getName().'">'.$this->getLabel().'</label>
                <select name="' . $this->getName() . '_hour" id="' . $this->getName()
                . '_hour" onchange="javascript:updateHiddenField(' . "'"
                . $this->getName() . "'" . ')" class="timeInput hour">';
        for ($i = 1; $i <= 12; $i++) {
            $form .= '
                    <option';
            if ($hour == $i) {
                $form .= ' selected';
            }
            $form .= '>'.$i.'</option>';
        }
        $form .= '
                </select>
                <select name="' . $this->getName() . '_minute" id="' . $this->getName()
                 . '_minute" onchange="javascript:updateHiddenField(' . "'" . $this->getName()
                 . "'" . ')" class="timeInput minute">';
        for ($i = 0; $i <= 60; $i += 5) {
            if (strlen($i) == 1) {
                $i = "0".$i;
            }
            $form .= '
                    <option';
            if ($minute == $i) {
                $form .= ' selected';
            }
            $form .= '>'.$i.'</option>';
        }
        $form .= '
                </select>
                <select name="' . $this->getName() . '_ampm" id="' . $this->getName()
                 . '_ampm" onchange="javascript:updateHiddenField(' . "'" . $this->getName()
                 . "'" . ')" class="timeInput ampm">';
        $form .= '
                    <option';
        if ($ampm == 'am') {
            $form .= ' selected';
        }
        $form .= '>am</option>
                <option';
        if ($ampm == 'pm') {
            $form .= ' selected';
        }
        $form .= '>pm</option>
            </select>';
        $hidden = new HiddenInput($this->getName(), $this->getValue());
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
                <input type="text" name="state" id="state" value="' . $this->state
               . '" class="textInput state" size="2" maxlength="2"/>
                <label class="inlineLabel" for="zip">Zip</label>
                <input type="text" name="zip" id="zip" value="' . $this->zip
               . '" class="textInput zip" size="5" maxlength="5"/>
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
