<?php

namespace Travi\framework\photos;

use Travi\framework\photos\Media;

class Video extends Media
{
    private $mobile;
    private $standard;
    private $highDef;

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile($version)
    {
        $this->mobile = $version;
    }

    public function getStandard()
    {
        return $this->standard;
    }

    public function setStandard($version)
    {
        $this->standard = $version;
    }

    public function getHighDef()
    {
        return $this->highDef;
    }

    public function setHighDef($version)
    {
        $this->highDef = $version;
    }
}