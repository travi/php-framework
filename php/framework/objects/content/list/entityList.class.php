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
        if(!empty($confirmation))
        {
            $this->addJavaScript('jqueryUi');
            $this->addJsInit('$("li.'.strtolower($text).'-item").click(function(){
                                    $(this).dialog({
                                        title:      "Are you sure?",
                                        modal:      true,
                                        resizable:  false,
                                        buttons:    [
                                            {
                                                text:   "'.$text.'",
                                                click:  function(){
                                                            $(this).dialog("close");
                                                        }
                                            },
                                            {
                                                text:   "Cancel",
                                                click:  function(){
                                                            return false;
                                                        }
                                            }
                                        ]
                                    });
                                    return false;
                                });');
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
}
?>