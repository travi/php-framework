<?php
/**
 * Created on Jan 25, 2011
 * By Matt Travi
 * programmer@travi.org
 */

require_once('Input.php');
 

class FileInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->setClass("fileInput");
		$this->setType("file");
	}
}