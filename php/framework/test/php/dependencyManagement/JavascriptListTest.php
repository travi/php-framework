<?php

namespace dependencyManagement;

use PHPUnit_Framework_TestCase;
use travi\framework\dependencyManagement\DependencyManager;
use travi\framework\dependencyManagement\JavascriptList;
use travi\framework\utilities\Environment;

class JavascriptListTest extends PHPUnit_Framework_TestCase
{
    private $environment;
    /** @var  JavascriptList */
    private $list;

    public function setUp()
    {
        $this->environment = $this->getMock('Environment');

        $this->list = new JavascriptList();
        $this->list->setEnvironment($this->environment);
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

    public function testThatFullSourceIsReturnedWhenLocal()
    {
        $someScript = '/resources/js/some script';

        $this->list->add($someScript);

        $this->assertEquals(array($someScript), $this->list->get());
    }

    public function testThatListIsMinifiedWhenNotLocal()
    {
        $this->list->add('/resources/js/some script');

        $this->assertEquals(
            array('/resources' . DependencyManager::MIN_DIR . '/js/some script'),
            $this->list->get()
        );
    }
}