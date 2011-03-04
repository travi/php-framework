<?php
/**
 * Created on Jan 26, 2011
 * By Matt Travi
 * programmer@travi.org
 */

require_once('Input.php');

class HiddenInput extends Input
{
	public function __construct($options)
	{
		parent::__construct($options);
		$this->setType("hidden");
        $this->setTemplate('components/form/input.tpl');
	}
}
 
