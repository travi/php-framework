<?php

namespace travi\framework\view\objects;

class LinkView
{

    public $text;
    public $url;

    function __construct($text, $url)
    {
        $this->text = $text;
        $this->url = $url;
    }
}