<?php

require_once('Input.php');


class TextInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->class = "textInput";
		$this->type = "text";
	}
}