<?php

namespace travi\framework\dependencyManagement;

use travi\framework\exception\UnknownDependencyListException;
use travi\framework\utilities\Environment;

class DependencyListFactory
{
    /** @var  Environment */
    private $environment;

    public function createList($type)
    {
        switch ($type) {
        case 'js':
            $javascriptList = new JavascriptList();

            $javascriptList->setMinifier($this->getMinifier());

            return $javascriptList;
        default:
            throw new UnknownDependencyListException();
        }
    }

    /**
     * @param $environment Environment
     * @PdInject environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return Minifier
     */
    private function getMinifier()
    {
        $minifier = new Minifier();

        $minifier->setEnvironment($this->environment);

        return $minifier;
    }

}