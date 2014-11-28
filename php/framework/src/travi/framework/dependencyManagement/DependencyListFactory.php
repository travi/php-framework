<?php

namespace travi\framework\dependencyManagement;

use travi\framework\exception\UnknownDependencyListException;

class DependencyListFactory
{
    public function createList($type)
    {
        switch ($type) {
        case 'js':
            return new JavascriptList();
        default:
            throw new UnknownDependencyListException();
        }
    }

}