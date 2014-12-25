<?php
namespace travi\framework\mappers;

use travi\framework\components\Forms\Form;

abstract class AbstractMapper
{
    /**
     * @param $form Form
     * @param $values
     */
    protected function setFieldValues($form, $values)
    {
        foreach ($values as $field => $value) {
            $form->getFieldByName($field)->setValue($value);
        }
    }

    /**
     * @param $form Form
     */
    protected function mapRequestValuesToForm($form)
    {
        $this->setFieldValues($form, $_POST);
    }
}
