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
        $this->addJavaScript('jquery');
        $this->addJsInit('$("li.remove-item form")
                                .hide()
                                .after("<a class=\'item-action\' href=\'#\'>Remove<\/a>")
                                    .next()
                                    .click(function(){
                                        $(this).prev("form").submit();
                                        return false;
                                    });');
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
            $this->addJsInit('$("body").append("<div id=\'confirmation\' title=\'Are you sure?\'>'.$confirmation.'<\/div>");
                                $("#confirmation").dialog({
                                    autoOpen:   false,
                                    modal:      true,
                                    resizable:  false
                                });
                                $("form.item-action").submit(function(){
                                    $form = $(this);
                                    $("#confirmation").dialog("option", "buttons", {
                                        "'.$text.'":function(){
                                                        $(this).dialog("close");
                                                        $form.unbind("submit").submit();
                                                    },
                                        "Cancel":   function(){
                                                        $(this).dialog("close");
                                                    }
                                    });
                                    $("#confirmation").dialog("open");
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