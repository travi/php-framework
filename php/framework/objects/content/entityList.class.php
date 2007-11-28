<?php
/*
 * Created on Oct 3, 2006
 * By Matt Travi
 * programmer@travi.org
 */

class EntityList
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
		$this->actions["$text"] = array($link,$confirmation);
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
    function setType($type)
    {
    	$this->type = $type;
    }
    function prependRemoveConfirmation($text)
    {
		$this->preConf = $text;
    }
    function addDetail($detail)
    {
    	array_push($this->details, $detail);
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
      					<dd><div class="actions">
      						<p>';
		$i = 0;

		foreach($actions as $text => $details)
		{
			list($link,$confirmation) = $details;

			if(empty($this->activeActions[$text]))
			{
				if($i != 0)
					$entity .= ' | ';

				$entity .= '
								<a href="'.$link.$this->id.'"';
				if(!empty($confirmation))
				{
					$entity .= ' onclick="if (confirm('."'".$this->preConf.$confirmation."'".')) return true; else return false;"';
				}

				$entity .= '>'.$text.'</a>';

				$i++;
			}
		}
		$entity .= '
							</p>
      					</div>
    				</dd>
  				</dl>
  			</div>';

  		return $entity;
    }
}
?>