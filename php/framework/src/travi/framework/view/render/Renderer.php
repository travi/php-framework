<?php

namespace travi\framework\view\render;

abstract class Renderer
{
    public function format($data, $page = null)
    {
        return print_r($data);
    }

    protected function convertDataToNestedAssocArray($data)
    {
        $result = array();

        foreach ($data as $key => $item) {
            $result[$key] = $this->objectToArray($item);
        }

        return $result;
    }

    protected function objectToArray($item)
    {
        if (is_object($item)) {
            $itemResult = array();
            $ref = new \ReflectionClass($item);

            foreach (array_values($ref->getMethods()) as $method) {
                if ($this->methodIsPublicGetter($method)) {
                    $value = $method->invoke($item);

                    if (is_object($value) || is_array($value)) {
                        $value = $this->objectToArray($value);
                    }
                    $itemResult[$this->getKeyFromMethodName($method)] = $value;
                }
                $result = array_filter($itemResult);
            }
        } elseif (is_array($item)) {
            $result = $this->convertDataToNestedAssocArray($item);
        } else {
            $result = $item;
        }

        return $result;
    }

    /**
     * @param $method \ReflectionMethod
     * @return bool
     */
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