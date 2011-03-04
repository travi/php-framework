<?php
/**
 * Created on 2/19/11
 * By Matt Travi
 * programmer@travi.org
 */
require_once('Choices.php');

class CheckBoxes extends Choices
{
	public function __construct($options=array())
	{
		parent::__construct($options);
		$this->type = "checkbox";
		$this->class = "checkbox";
	}
}
 
