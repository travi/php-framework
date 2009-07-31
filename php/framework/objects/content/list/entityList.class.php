<?php
/*
 * Created on Oct 3, 2006
 * By Matt Travi
 * programmer@travi.org
 */

class EntityList extends ContentObject
{
	var $entities = array();
	var $actions = array();

	function __construct()
	{
		$this->addStyleSheet('/resources/shared/css/travi.entities.css');
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
	function __toString()
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
//		foreach($actions as $action)
//		{
//			$this->disableAction($action,true);
//		}
    }
    function disableAction($text,$active)
    {
		$this->activeActions["$text"] = "$active";
    }
    function toString($primaryActions=array())
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

		foreach($primaryActions as $text => $details)
		{
			if(empty($this->activeActions[$text]))
			{
				$entity .= '
								<li class="'.strtolower($text).'-item"><a class="item-action" href="'.$details['link'].$this->id.'"';
				if(!empty($details['confirmation']))
				{
					$entity .= ' onclick="if (confirm('."'".$this->preConf.$details['confirmation']."'".')) return true; else return false;"';
				}

				$entity .= '>';
				
				$entity .= $text.'</a></li>';
			}
		}
		
		$entity .= '
							</ul>';
							
		if(!empty($this->extraActionRows))
		{
			$entity .= '
							<ul class="actions">';
			
			foreach($this->extraActionRows as $row)
			{
				foreach($row as $actions)
				{
					if(!isset($actions['active']) || $actions['active'] == TRUE)
					{
						$entity .= '
									<li class="item-action '.$actions['class'].'"><a href="'.$actions['link'].$this->id.'"';
						if(!empty($actions["$action_text"]['confirmation']))
						{
							$entity .= ' onclick="if (confirm('."'".$this->preConf.$actions["$action_text"]['confirmation']."'".')) return true; else return false;"';
						}
	
						$entity .= '>'.$actions['text'].'</a></li>';
					}
				}
			}
			
			$entity .= '
						</ul>';
		}
		$entity .= '
					</dd>
  				</dl>
  			</div>';

  		return $entity;
    }
}
?>