<?php

namespace travi\framework\content;

use travi\framework\content\ContentObject;

class GalleryComponent extends ContentObject
{
    function __construct()
    {
        $this->addJavaScript('gallery');
    }
}