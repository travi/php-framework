<?php

class JsonRenderer extends Renderer
{
    public function format($data)
    {
        header('Content-Type: application/json');

        $data = $this->convertObjectsToAssocArrays($data);

        //TODO: if content is array of size 1, should only encode that element rather than the array
        return json_encode($data);
    }
}