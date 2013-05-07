<?php

use Travi\framework\components\Forms\Form;

abstract class CrudMapper
{
    /**
     * @param $entity
     * @param $action
     * @return Form
     */
    abstract public function mapToForm($entity, $action);

    /**
     * @abstract
     * @param $form Form
     */
    abstract public function mapFromForm($form);

    /**
     * @abstract
     * @return Form
     */
    abstract public function mapRequestToForm();

    /**
     * @abstract
     * @param $form Form
     */
    abstract protected function addFieldsTo($form);

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
