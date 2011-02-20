<?php
/**
 * Created on 2/19/11
 * By Matt Travi
 * programmer@travi.org
 */
require_once('Choices.php');

class RadioButtons extends Choices
{
	public function __construct($options=array())
	{
		parent::__construct($options);
		$this->type = "radio";
		$this->class = "radioButton";
	}
}
 
