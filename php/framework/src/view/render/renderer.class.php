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
            $itemResult = array();

            if (is_object($item)) {
                $ref = new ReflectionClass($item);

                foreach (array_values($ref->getMethods()) as $method) {
                    if ($this->methodIsPublicGetter($method)) {
                        $value = $method->invoke($item);
                        if (is_object($value) || is_array($value)) {
                            $value = $this->convertObjectsToAssocArrays($value);
                        }
                        $itemResult[$this->getKeyFromMethodName($method)] = $value;
                    }
                    $result[$key] = array_filter($itemResult);
                }
            } elseif (is_array($item)) {
                $result[$key] = $this->convertObjectsToAssocArrays($item);
            } else {
                $result[$key] = $item;
            }
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