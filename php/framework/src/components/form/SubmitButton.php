<?php
/**
 * Created on Jan 26, 2011
 * By Matt Travi
 * programmer@travi.org
 */

require_once('inputs/Input.php');


class SubmitButton extends Input
{
    protected $confirmation;

    public function __construct($options)
    {
        parent::__construct($options);
        $this->label = "";
        $this->setType("submit");
        $this->setName("Submit");
        if(!empty($options['class']))
            $this->setClass($options['class']);
        else
            $this->setClass("submitButton");
        $this->setValue($options['label']);
        $this->setTemplate('components/form/input.tpl');
        $this->addJavaScript('jqueryUi');
        $this->addJsInit('$("input[type=submit]").button()');
    }

    //TODO need to replace this technique using UI dialog
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;
    }

//    public function __toString()
//    {
//        $string = '
//                        <input type="'.$this->type.'" name="'.$this->name.'"
//id="'.$this->name.'" value="'.$this->value.
//                    '" class="'.$this->class.'"';
//        if(!empty($this->confirmation))
//            $string .= ' onclick="if (confirm(\''.$this->confirmation.'\'))
//        return true; else return false;"';
//        $string .= '/>';
//
//        return $string;
//    }
}
