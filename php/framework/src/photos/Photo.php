<?php
require_once 'License.php';

class Photo
{
    private $uri;
    private $thumbnail;
    /** @var License */
    private $license;

    public function someFunc() {

    }

    public function getOriginal()
    {
        return $this->uri;
    }

    public function setOriginal($uri)
    {
        $this->uri = $uri;
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
