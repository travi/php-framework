<?php
require_once 'Thumbnail.php';

class Album
{
    private $title;
    private $url;
    private $photos = array();
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

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPhotos()
    {
        return $this->photos;
    }

    public function setPhotos($photos)
    {
        $this->photos = $photos;
    }

    public function getTotalPhotoCount()
    {
        return $this->totalPhotos;
    }

    public function setTotalPhotoCount($count)
    {
        $this->totalPhotos = $count;
    }
}
