<?php

abstract class Renderer
{
    public function format($data)
    {
        return print_r($data);
    }

    protected function convertObjectsToAssocArrays($data)
    {
        $result = array();

        foreach ($data as $key => $item) {
            $result[$key] = $this->object_to_array($item);
        }

        return $result;
    }

    protected function object_to_array($item)
    {
        if (is_object($item)) {
            $itemResult = array();
            $ref = new ReflectionClass($item);

            foreach (array_values($ref->getMethods()) as $method) {
                if ($this->methodIsPublicGetter($method)) {
                    $value = $method->invoke($item);

                    if (is_object($value) || is_array($value)) {
                        $value = $this->object_to_array($value);
                    }
                    $itemResult[$this->getKeyFromMethodName($method)] = $value;
                }
                $result = array_filter($itemResult);
            }
        } elseif (is_array($item)) {
            $result = $this->convertObjectsToAssocArrays($item);
        } else {
            $result = $item;
        }

        return $result;
    }

    protected function methodIsPublicGetter($method)
    {
        return (0 === strpos($method->name, "get")) && $method->isPublic();
    }

    protected function getKeyFromMethodName($method)
    {
        $key = substr($method->name, 3);
        $key[0] = strtolower($key[0]);
        return $key;
    }
}