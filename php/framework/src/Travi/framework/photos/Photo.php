<?php

namespace travi\framework\photos;

use travi\framework\photos\Media;

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
