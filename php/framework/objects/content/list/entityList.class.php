<?php

class EntityList extends ContentObject implements IteratorAggregate
{
    private $entities = array();
    private $actions = array();
    private $limit;
    private $offset;
    private $totalEntities;

    public function __construct()
    {
        $this->addJavaScript('entityList');
    }
    public function setEdit($script, $confirmation="")
    {
        $this->addAction("Edit", $script, $confirmation);
    }
    public function setRemove($script, $confirmation="")
    {
        $this->addAction("Remove", $script, $confirmation);
    }
    public function addEntity($entity)
    {
        array_push($this->entities, $entity);
    }
    public function addAction($text, $link, $confirmation="")
    {
        $this->actions["$text"] = array('link' => $link, 'confirmation' => $confirmation);
        if (!empty($confirmation)) {
            $this->addJsInit(
                'travi.framework.entityList.setConfirmationMessage("'.$confirmation.'");
                                travi.framework.entityList.setButtonText("'.$text.'");'
            );
        }
    }
    public function getActions()
    {
        return $this->actions;
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

    public function getIterator()
    {
        return new ArrayIterator(get_object_vars($this));
    }
}
?>