<?php

namespace Travi\framework\content;

use Travi\framework\content\ContentObject;

class GalleryComponent extends ContentObject
{
    function GalleryComponent()
    {
        $this->addJavaScript('gallery');
    }
}