<?php

namespace Travi\framework\photos;

use Travi\framework\photos\Media;

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
