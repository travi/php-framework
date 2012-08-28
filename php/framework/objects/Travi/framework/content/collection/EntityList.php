<?php

namespace Travi\framework\content\collection;

use \Travi\framework\content\ContentObject;

class EntityList extends ContentObject
{
    private $entities = array();
    private $actions = array();
    private $limit;
    private $offset;
    private $totalEntities;

    const EDIT_KEY = "Edit";

    public function __construct()
    {
        $this->addJavaScript('entityList');
        $this->addAction(self::EDIT_KEY, '');
    }

    public function setEdit($path)
    {
        $this->entities[self::EDIT_KEY] = $path;
    }

    public function setRemove($path, $confirmation="")
    {
        $this->addAction("Remove", $path, $confirmation);
    }
    public function addEntity($entity)
    {
        array_push($this->entities, $entity);
    }
    public function addAction($text, $link, $confirmation="")
    {
        array_push(
            $this->actions,
            array(
                'text' => $text,
                'link' => $link
            )
        );

        if (!empty($confirmation)) {
            $this->addJsInit(
                '
                travi.framework.entityList.setConfirmationMessage("'.$confirmation.'");
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
}