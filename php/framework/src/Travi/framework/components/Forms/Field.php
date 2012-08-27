<?php

namespace Travi\framework\components\Forms;

interface Field
{
    public function getValidations();
    public function getName();
    public function setLabel($label);
    public function getLabel();
    public function addValidation($validation);
    public function setValidationError($message);
    public function getValidationError();
    public function isValid();
}