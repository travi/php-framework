<?php
/*
 * Created on Sept 12, 2010
 * By Matt Travi
 */

abstract class dataFormatter
{
	protected $data = array();
	
	public function setData($data = array())
	{
		$this->data = $data;
	}
	public function format()
	{
		return print_r($this->data);
	}
}

class jsonFormatter extends dataFormatter
{
	public function format()
	{
		require_once(INCLUDE_PATH.'php/utilities/JSON.php');
		
		$json = new Services_JSON();
    	$output = $json->encode($this->data);
		header('Content-Type: application/json');
		
		return $output;
	}
}
?>