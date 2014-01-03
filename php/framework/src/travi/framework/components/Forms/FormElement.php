<?php

namespace travi\framework\components\Forms;

interface FormElement
{
    public function getValidations();
    public function isValid();
}
