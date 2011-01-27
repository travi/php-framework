<?php

require_once('Input.php');


class TextInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->setClass("textInput");
		$this->setType("text");
	}
}