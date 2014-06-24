<?php

namespace travi\framework\view\objects;

class LinkView extends AbstractView
{
    protected $template = 'components/link.tpl';

    public $text;
    public $url;

    function __construct($text, $url)
    {
        $this->text = $text;
        $this->url = $url;
    }
}