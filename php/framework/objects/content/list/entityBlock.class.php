<?php
/**
 * User: travi
 * Date: Nov 25, 2010
 * Time: 11:25:12 PM
 */


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
