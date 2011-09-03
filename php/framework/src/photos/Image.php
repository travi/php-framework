<?php
 
abstract class Image implements IteratorAggregate
{
    protected $url;

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getIterator()
    {
        return new ArrayIterator(get_object_vars($this));
    }
}
