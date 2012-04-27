<?php

require_once 'inputs/Input.php';

class SubmitButton extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->label = "";
        $this->setType("submit");
        $this->setName("Submit");
        if (!empty($options['class'])) {
            $this->setClass($options['class']);
        } else {
            $this->setClass("submitButton");
        }
        $this->setValue($options['label']);
        $this->setTemplate('components/form/input.tpl');
        $this->addJavaScript('jqueryUi');
        $this->addJsInit('$("input[type=submit]").button()');
    }

    public function isOuterButton($bool)
    {
        $this->isOuterButton = $bool;
    }

    public function getClass()
    {
        if ($this->isOuterButton) {
            $this->class .= ' outerButton';
        }

        return $this->class;
    }


}
