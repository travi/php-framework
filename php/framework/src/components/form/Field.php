<?php

require_once dirname(__FILE__).'/../../../objects/content/contentObject.class.php';

interface Field
{
    public function getValidations();
    public function getName();
    public function addValidation($validation);
}