<?php

require_once('contentObject.class.php');

class HtmlElement extends ContentObject
{
	var $attributes = array();
	var $tag;
	var $contents;

	function appendToContents($contents)
	{
		$this->contents .= $contents;
	}

	function toString()
	{
		return "<$this->tag>$this->contents</$this->tag>";
	}
}

class Paragraph extends HtmlElement
{
	function Paragraph($contents="")
	{
		$this->tag = "p";
		$this->contents = $contents;
	}

}

class HtmlList extends HtmlElement
{
	var $listItems = array();

	function addListItem($item)
	{
		array_push($this->listItems,$item);
	}

	function toString()
	{
		$list = "<$this->tag>";
		foreach($this->listItems as $listItem)
		{
			$list .= "
				<li>$listItem</li>";
		}
		$list .= "
			</$this->tag>";

		return $list;
	}
}

/*class UnorderedList extends HtmlList
{
	function UnorderedList()
	{
		$this->tag = "ul";
	}
}*/

class Section extends ContentObject
{
	var $heading;
	var $body;
	var $headingTag;

	function setHeading($heading)
	{
		$this->heading = $heading;
	}

	function appendToSection($add)
	{
		if(is_a($add,'HtmlElement'))
		{
			$this->body .= $add->toString();
		}
		else
			$this->body .= $add;
	}

	function toString()
	{
		return "
			<$this->headingTag>$this->heading</$this->headingTag>
			$this->body";
	}
}

class PrimarySection extends Section
{
	function PrimarySection($heading="",$body="")
	{
		$this->headingTag = "h2";
		$this->heading = $heading;
		$this->appendToSection($body);
	}
}

class SecondarySection extends Section
{
	function SecondarySection($heading="",$body="")
	{
		$this->headingTag = "h3";
		$this->heading = $heading;
		$this->appendToSection($body);
	}
}

class SecondaryNavigation extends Section
{
	function SecondaryNavigation($heading="",$body="")
	{
		$this->headingTag = "h3";
		$this->heading = $heading;
		$this->appendToSection($body);
	}

	function toString()
	{
		return '
	<div class="subNav">
		'.parent::toString().'
	</div>';
	}
}

class Highlight extends Section
{
	function Highlight($heading="",$body="")
	{
		$this->headingTag = "h3";
		$this->heading = $heading;
		$this->appendToSection($body);
	}

	function toString()
	{
		return '
	<div class="highlight">
		<div class="highlightBottom">
		'.parent::toString().'
		</div>
	</div>';
	}
}
?>