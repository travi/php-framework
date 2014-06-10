<?php

namespace travi\framework\collection;

use travi\framework\view\objects\LinkView;

class EntityBlock
{
    public $id;
    public $selfLink;

    private $title;
    private $type;
    private $preConf;
    private $summary;
    private $details = array();
    private $extraActionRows = array();

    private $actions = array(
        'primary' => array()
    );

    public function __construct($id, $urlPrefix)
    {
        $this->id = $id;
        $this->urlPrefix = $urlPrefix;
        $this->selfLink = $urlPrefix . $id;

        $this->addPrimaryAction('Edit');
    }

    public function addRemoveAction()
    {
        $this->actions['primary']['remove'] = new LinkView(
            'Remove',
            $this->urlPrefix . $this->id
        );
    }

    public function getPrimaryActions()
    {
        return $this->actions['primary'];
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }
    public function prependRemoveConfirmation($text)
    {
        $this->preConf = $text;
    }
    public function getPrependedRemovalConfirmation()
    {
        return $this->preConf;
    }
    public function setType($type)
    {
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @deprecated
     * @param $detail
     */
    public function addDetail($detail)
    {
        array_push($this->details, $detail);
    }
    /**
     * @deprecated
     * @return array
     */
    public function getDetails()
    {
        return $this->details;
    }
    public function addActionRow($actions=array())
    {
        array_push($this->extraActionRows, $actions);
    }

    public function getExtraActionRows()
    {
        return $this->extraActionRows;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    /**
     * @param $actionName
     */
    private function addPrimaryAction($actionName)
    {
        $lowerCaseName = strtolower($actionName);

        $this->actions['primary'][$lowerCaseName] = new LinkView(
            $actionName,
            $this->urlPrefix . $this->id . '/' . $lowerCaseName
        );
    }
}
