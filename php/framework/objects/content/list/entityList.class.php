<?php
/*
 * Created on Oct 3, 2006
 * By Matt Travi
 * programmer@travi.org
 */

class EntityList extends ContentObject
{
	private $entities = array();
	private $actions = array();

	public function __construct()
	{
		$this->addStyleSheet('/resources/shared/css/travi.entities.css');
	}
	public function setEdit($script,$confirmation="")
	{
		$this->addAction("Edit",$script,$confirmation);
	}
	public function setRemove($script,$confirmation="")
	{
		$this->addAction("Remove",$script,$confirmation);
	}
	public function addEntity($entity)
	{
		array_push($this->entities,$entity);
	}
    public function addAction($text,$link,$confirmation="")
    {
		$this->actions["$text"] = array('link' => $link, 'confirmation' => $confirmation);
    }
    public function getActions()
    {
        return $this->actions;
    }
    public function getEntities()
    {
        return $this->entities;
    }
}

class EntityBlock
{
	private $title;
	private $id;
	private $type;
	private $preConf;
	private $details = array();
	private $activeActions = array();
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
    public function addDetail($detail)
    {
    	array_push($this->details, $detail);
    }
    public function getDetails()
    {
        return $this->details;
    }
    public function addActionRow($actions=array())
    {
		array_push($this->extraActionRows,$actions);
//		foreach($actions as $action)
//		{
//			$this->disableAction($action,true);
//		}
    }
    private function disableAction($text,$active)
    {
		$this->activeActions["$text"] = "$active";
    }
}
?>