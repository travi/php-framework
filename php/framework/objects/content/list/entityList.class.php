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
    private $limit;
    private $offset;
    private $totalEntities;

	public function __construct()
	{
		$this->addStyleSheet('/resources/shared/css/travi.entities.css');
        $this->addJavaScript('jquery');
        $this->addJavaScript('/resources/shared/js/list/entityList.js');
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
        if(!empty($confirmation))
        {
            $this->addJavaScript('jqueryUi');
            $this->addJsInit('travi.framework.entityList.setConfirmationMessage("'.$confirmation.'");
                                travi.framework.entityList.setButtonText("'.$text.'");');
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
?>