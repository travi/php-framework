<?php

abstract class dataFormatter
{
    protected $data = array();

    public function setData($data = array())
    {
        $this->data = $data;
    }
    public function format()
    {
        return print_r($this->data);
    }
}

class jsonFormatter extends dataFormatter
{
    public function format()
    {
        $output = json_encode($this->data);
        header('Content-Type: application/json');

        return $output;
    }
}
