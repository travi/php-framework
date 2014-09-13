<?php

namespace travi\framework\photos;

class License
{

    private $url;
    private $name;
    private $id;

    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
