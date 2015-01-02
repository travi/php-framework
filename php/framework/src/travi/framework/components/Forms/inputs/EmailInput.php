<?php

namespace travi\framework\components\Forms\inputs;


class EmailInput extends Input
{
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
        return 1 === preg_match('/.+@.+\..+/', $this->getValue());
    }

}