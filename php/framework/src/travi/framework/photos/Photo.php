<?php

namespace travi\framework\photos;

class Photo extends Media
{
    public function getOriginal()
    {
        return $this->url;
    }

    public function setOriginal($uri)
    {
        $this->url = $uri;
    }
}
