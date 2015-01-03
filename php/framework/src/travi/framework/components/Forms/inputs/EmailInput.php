<?php

namespace travi\framework\components\Forms\inputs;


class EmailInput extends Input
{
    public function __construct($options = array())
    {
        $this->setClass("textInput");
        $this->setType('email');
    }

    public function isValid()
    {
        $isValid = parent::isValid();

        if ($isValid && !$this->isValidEmailFormat()) {
            $this->setValidationError('A valid email address must be supplied');
            return false;
        }

        return $isValid;
    }

    /**
     * @return bool
     */
    private function isValidEmailFormat()
    {
        $value = $this->getValue();

        return empty($value) || 1 === preg_match('/.+@.+\..+/', $value);
    }

}