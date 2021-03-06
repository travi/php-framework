<?php
namespace travi\framework\mappers;

use travi\framework\components\Forms\Form;
use travi\framework\collection\EntityList;
use travi\framework\collection\EntityBlock;

abstract class CrudMapper extends AbstractMapper
{
    /**
     * @param $entity
     * @internal param $action
     * @return Form
     */
    abstract public function mapToForm($entity = null);

    /**
     * @param $entities
     * @internal param $entity
     * @return EntityList
     */
    abstract public function mapListToEntityList($entities);

    /**
     * @param $entity
     * @return EntityBlock
     */
    abstract public function mapToEntityBlock($entity);

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
}
