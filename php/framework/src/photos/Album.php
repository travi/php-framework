<?php
require_once 'Thumbnail.php';

class Album
{
    private $title;
    private $url;
    /** @var Thumbnail */
    private $thumbnail;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
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
