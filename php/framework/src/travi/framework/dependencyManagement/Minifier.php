<?php

namespace travi\framework\dependencyManagement;

use travi\framework\utilities\Environment;

class Minifier
{
    /** @var  Environment */
    private $environment;

    public function minifyList($list)
    {
        if (!$this->environment->isLocal()) {
            foreach ($list as &$dependency) {
                $dependency = $this->minify($dependency);
            }
        }

        return $list;
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

    /**
     * @param $environment Environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}