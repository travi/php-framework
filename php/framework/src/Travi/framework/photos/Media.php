<?php

namespace Travi\framework\photos;

use Travi\framework\photos\License,
    Travi\framework\photos\Thumbnail;

class Media
{
    /** @var License */
    protected $license;
    /** @var Thumbnail */
    private $thumbnail;

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense($license)
    {
        $this->license = $license;
    }

    public function getCaption()
    {
        return $this->caption;
    }

    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    public function getPreview()
    {
        return $this->preview;
    }

    public function setPreview($url)
    {
        $this->preview = $url;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumb)
    {
        $this->thumbnail = $thumb;
    }
}