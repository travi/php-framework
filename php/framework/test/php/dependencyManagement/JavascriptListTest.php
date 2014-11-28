<?php

namespace dependencyManagement;

use PHPUnit_Framework_TestCase;
use travi\framework\dependencyManagement\JavascriptList;

class JavascriptListTest extends PHPUnit_Framework_TestCase
{
    /** @var  JavascriptList */
    private $list;

    public function setUp()
    {
        $this->list = new JavascriptList();
    }

    public function testThatListIsEmptyIfNothingHasBeenAdded()
    {
        $this->assertEquals(array(), $this->list->get());
    }

    public function testThatJavascriptFileCanBeAddedToTheList()
    {
        $someScript = 'jquery';

        $this->list->add($someScript);

        $this->assertEquals(array($someScript), $this->list->get());
    }
}