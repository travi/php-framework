<?php

namespace travi\framework\dependencyManagement;


interface DependencyList
{
    /**
     * @param $dependency
     * @return void
     */
    function add($dependency);

    /**
     * @return string[]
     */
    function get();
}