<?php

namespace travi\framework\photos;

use travi\framework\photos\Media;

class Video extends Media
{
    private $mobile;
    private $standard;
    private $highDef;

    private $width;
    private $height;

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

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public function getWidth()
    {
        return $this->width;
    }

    public function setHeight($height)
    {
        $this->height = $height;
    }

    public function getHeight()
    {
        return $this->height;
    }
}