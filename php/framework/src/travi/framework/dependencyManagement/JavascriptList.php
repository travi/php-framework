<?php

namespace travi\framework\dependencyManagement;

class JavascriptList implements DependencyList
{
    /** @var  Minifier */
    private $minifier;

    private $list = array();

    /**
     * @param $dependency
     * @return void
     */
    public function add($dependency)
    {
        if ($this->shouldBeAdded($dependency)) {
            array_push($this->list, $dependency);
        }
    }

    /**
     * @return string[]
     */
    public function get()
    {
        $list = $this->minifier->minifyList($this->list);

        return $list;
    }

    /**
     * @param $dependency
     * @return bool
     */
    private function shouldBeAdded($dependency)
    {
        return '' !== $dependency;
    }

    public function setMinifier($minifier)
    {
        $this->minifier = $minifier;
    }
}