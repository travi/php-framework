<?php
namespace Travi\framework\mappers;

use Travi\framework\components\Forms\Form;
use Travi\framework\content\collection\EntityList;

abstract class CrudMapper
{
    /**
     * @param $entity
     * @param $action
     * @return Form
     */
    abstract public function mapToForm($entity = null);

    /**
     * @param $entity
     * @return EntityList
     */
    abstract public function mapListToEntityList($entity);

    /**
     * @abstract
     * @param $form Form
     */
    abstract public function mapFromForm($form);

    /**
     * @abstract
     * @param $form Form
     */
    abstract protected function addFieldsTo($form);

    /**
     * @abstract
     * @return Form
     */
    abstract public function mapRequestToForm();


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
