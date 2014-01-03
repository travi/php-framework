<?php

namespace travi\framework\view\render;

use travi\framework\view\render\Renderer;

class JsonRenderer extends Renderer
{
    public function format($data)
    {
        header('Content-Type: application/json');

        $data = $this->convertDataToNestedAssocArray($data);

        //TODO: if content is array of size 1, should only encode that element rather than the array
        return json_encode($data);
    }
}