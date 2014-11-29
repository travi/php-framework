<?php

namespace travi\framework\dependencyManagement;


interface DependencyList
{
    /**
     * @param $dependency
     * @return void
     */
    public function add($dependency);

    /**
     * @return string[]
     */
    public function get();
}