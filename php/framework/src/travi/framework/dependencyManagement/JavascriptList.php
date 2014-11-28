<?php

namespace travi\framework\dependencyManagement;


class JavascriptList implements DependencyList
{
    private $list = array();

    /**
     * @param $dependency
     * @return void
     */
    function add($dependency)
    {
        array_push($this->list, $dependency);
    }

    /**
     * @return string[]
     */
    function get()
    {
        return $this->list;
    }
}