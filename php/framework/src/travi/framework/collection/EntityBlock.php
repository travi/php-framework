<?php

namespace travi\framework\collection;

class EntityBlock
{
    private $title;
    private $id;
    private $type;
    private $preConf;
    private $summary;
    private $details = array();
    private $extraActionRows = array();

    public function EntityBlock()
    {

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
}
