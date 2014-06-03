<?php

namespace travi\framework\content;

class GalleryComponent extends ContentObject
{
    function __construct()
    {
        $this->addJavaScript('gallery');
    }
}