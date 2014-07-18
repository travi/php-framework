<?php

namespace travi\framework\components\Forms;

use travi\framework\components\Forms\inputs\Input;

class SubmitButton extends Input
{
    private $isOuterButton;

    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->setType("submit");
        $this->setName("Submit");
        $this->setTemplate('components/form/input.tpl');
        $this->addJavaScript('buttons');

        if (!empty($options['class'])) {
            $this->setClass($options['class']);
        } else {
            $this->setClass("submitButton");
        }

        if (isset($options['label'])) {
            $this->setValue($options['label']);
        } else {
            $this->setValue('Submit');
        }
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
