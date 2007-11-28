<?php

class ajaxFileUpload extends ContentObject
{
	var $link;
	var $text;

    function ajaxFileUpload()
    {

    }
    function setLink($link)
    {
    	$this->link = $link;
    }
    function setText($text)
    {
    	$this->text = $text;
    }
    function toString()
    {
    	$string = '<a href="'.$this->link.'"><small>'.$this->text.'</small>';

    	return $string;
    }
}
?>