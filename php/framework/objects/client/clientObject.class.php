<?php

class ClientObject extends DependantObject
{
	var $file;
	
	function ClientObject($file,$dependencies=array())
	{
		$this->file = $file;
	}
}
?>