<?php

namespace travi\framework\view\objects;

use travi\framework\DependantObject;

abstract class AbstractView extends DependantObject
{
    protected $tags = array();

    public function addTag($tag)
    {
        array_push($this->tags, $tag);
    }

    public function getTags()
    {
        return $this->tags;
    }
}