<?php

namespace travi\framework\collection;

use \travi\framework\content\ContentObject;
use travi\framework\view\objects\LinkView;

class EntityList extends ContentObject
{
    private $entities = array();
    private $limit;
    private $offset;
    private $totalEntities;

    public $pluralType;

    /** @var LinkView */
    public $add;

    public function __construct()
    {
        $this->addJavaScript('entityList');
    }

    public function addEntity($entity)
    {
        array_push($this->entities, $entity);
    }

    public function getEntities()
    {
        return $this->entities;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function getNextOffset()
    {
        return $this->getOffset() + $this->getLimit();
    }

    public function getPrevOffset()
    {
        return $this->getOffset() - $this->getLimit();
    }

    public function setTotalEntities($total)
    {
        $this->totalEntities = $total;
    }

    public function getTotalEntities()
    {
        return $this->totalEntities;
    }
}