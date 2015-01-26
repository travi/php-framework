<?php

namespace travi\framework\components\Forms\inputs;


class EmailInput extends Input
{
    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->addValidation('email');
        $this->setClass("textInput");
        $this->setType('email');
    }

    public function isValid()
    {
        $isValid = parent::isValid();

        if ($isValid && !$this->isValidEmailFormat()) {
            $this->setValidationError('Please enter a valid email address.');
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