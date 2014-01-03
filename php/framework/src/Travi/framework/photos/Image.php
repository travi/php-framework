<?php

namespace travi\framework\photos;
 
abstract class Image
{
    protected $url;

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }
}
