<?php
require_once 'License.php';
require_once 'Image.php';

class Photo extends Image
{
    /** @var Thumbnail */
    private $thumbnail;
    /** @var License */
    private $license;

    public function getOriginal()
    {
        return $this->url;
    }

    public function setOriginal($uri)
    {
        $this->url = $uri;
    }

    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    public function setThumbnail($thumb)
    {
        $this->thumbnail = $thumb;
    }

    public function getLicense()
    {
        return $this->license;
    }

    public function setLicense($license)
    {
        $this->license = $license;
    }
}
