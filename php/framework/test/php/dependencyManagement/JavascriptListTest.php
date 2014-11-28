<?php

namespace dependencyManagement;

use PHPUnit_Framework_TestCase;
use travi\framework\dependencyManagement\JavascriptList;

class JavascriptListTest extends PHPUnit_Framework_TestCase
{

    public function testThatJavascriptFileCanBeAddedToTheList()
    {
        $someScript = 'jquery';
        $list = new JavascriptList();

        $list->add($someScript);

        $this->assertEquals(array($someScript), $list->get());
    }
}