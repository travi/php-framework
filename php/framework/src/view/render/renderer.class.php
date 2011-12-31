<?php

abstract class Renderer
{
    public function format($data)
    {
        return print_r($data);
    }

    protected function convertObjectsToAssocArrays($data)
    {
        return $this->object_to_array_through_getters($data);
    }

    protected function object_to_array_through_getters($data)
    {
        $result = array();

        foreach ($data as $key =>$item) {
            $itemResult = array();

            if (is_object($item)) {
                $ref = new ReflectionClass($item);

                foreach (array_values($ref->getMethods()) as $method) {
                    if ($this->methodIsPublicGetter($method)) {
                        $value = $method->invoke($item);
                        if (is_object($value) || is_array($value)) {
                            $value = $this->object_to_array_through_getters($value);
                        }
                        $itemResult[$this->getKeyFromMethodName($method)] = $value;
                    }
                }
            } elseif (is_array($item)) {
                $itemResult = $this->object_to_array_through_getters($item);
            } else {
                $itemResult = $item;
            }

            $result[$key] = array_filter($itemResult);
        }

        return array_filter($result);
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