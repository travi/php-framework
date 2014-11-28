<?php

namespace travi\framework\dependencyManagement;

use travi\framework\utilities\Environment;

class JavascriptList implements DependencyList
{
    /** @var  Environment */
    private $environment;

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
        $this->minifyList();

        return $this->list;
    }

    /**
     * @param $environment Environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    private function minifyList()
    {
        if (!$this->environment->isLocal()) {
            foreach ($this->list as &$dependency) {
                $dependency = $this->minify($dependency);
            }
        }
    }

    /**
     * @param $dependency
     * @return mixed
     */
    private function minify($dependency)
    {
        return preg_replace(
            '/\/(resources.*)\/(css|js)\//',
            '/$1' . DependencyManager::MIN_DIR . '/$2/',
            $dependency,
            1
        );
    }
}