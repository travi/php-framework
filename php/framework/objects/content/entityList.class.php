<?php
/*
 * Created on Oct 3, 2006
 * By Matt Travi
 * programmer@travi.org
 */

require_once('contentObject.class.php');

class EntityList extends ContentObject
{
	var $entities = array();
	var $actions = array();

	function EntityList()
	{

	}
	function setEdit($script,$confirmation="")
	{
		$this->addAction("Edit",$script,$confirmation);
	}
	function setRemove($script,$confirmation="")
	{
		$this->addAction("Remove",$script,$confirmation);
	}
	function addEntity($entity)
	{
		array_push($this->entities,$entity);
	}
    function addAction($text,$link,$confirmation="")
    {
		$this->actions["$text"] = array('link' => $link, 'confirmation' => $confirmation);
    }
	function toString()
	{
		$list = "";
		foreach($this->entities as $entity)
		{
			$list .= $entity->toString($this->actions);
		}
		return $list;
	}
}

class EntityBlock
{
	var $title;
	var $id;
	var $type;
	var $preConf;
	var $details = array();
	var $activeActions = array();
	var $extraActionRows = array();

	function EntityBlock()
    {

    }
    function setTitle($title)
    {
    	$this->title = $title;
    }
    function setId($id)
    {
    	$this->id = $id;
    }
    function prependRemoveConfirmation($text)
    {
		$this->preConf = $text;
    }
    function setType($type)
    {
    	$this->type = $type;
    }
    function addDetail($detail)
    {
    	array_push($this->details, $detail);
    }
    function addActionRow($actions=array())
    {
		array_push($this->extraActionRows,$actions);
		foreach($actions as $action)
		{
			$this->disableAction($action,true);
		}
    }
    function disableAction($text,$active)
    {
		$this->activeActions["$text"] = "$active";
    }
    function toString($actions=array())
    {
    	$entity = '
			<div class="entityBlock '.$this->type.'">
	      		<dl>
	        		<dt>'.$this->title.'</dt>';
		foreach($this->details as $detail)
		{
			$entity .= '
						<dd>'.$detail.'</dd>';
		}
		$entity .= '
						<dd>
							<ul class="actions">';
		$i = 0;

		$actionRows = array();
		$primaryActionRow = array();

		foreach($actions as $text => $details)
		{
			if(empty($this->activeActions[$text]))
			{
				$action = '
									<li class="'.strtolower($text).'-item"><a class="item-action" href="'.$details['link'].$this->id.'"';
				if(!empty($details['confirmation']))
				{
					$action .= ' onclick="if (confirm('."'".$this->preConf.$details['confirmation']."'".')) return true; else return false;"';
				}

				$action .= '>';
				
				$action .= $text.'</a></li>';

				array_push($primaryActionRow,$action);
			}
		}
		array_push($actionRows,implode(' ',$primaryActionRow));
		//array_push($actionRows,implode(' | ',$primaryActionRow));
		if(!empty($this->extraActionRows))
		{
			foreach($this->extraActionRows as $row)
			{
				$actionRow = array();
				foreach($row as $action_text)
				{
					$action = '
									<a href="'.$actions["$action_text"]['link'].$this->id.'"';
					if(!empty($actions["$action_text"]['confirmation']))
					{
						$action .= ' onclick="if (confirm('."'".$this->preConf.$actions["$action_text"]['confirmation']."'".')) return true; else return false;"';
					}

					$action .= '>'.$action_text.'</a>';

					array_push($actionRow,$action);
				}
				array_push($actionRows,implode(' | ',$actionRow));
			}
		}
		$entity .= implode("<br/>\n",$actionRows);
		$entity .= '
						</ul>
					</dd>
  				</dl>
  			</div>';

  		return $entity;
    }
}
?>