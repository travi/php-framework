<?php

use travi\framework\collection\EntityList;

class EntityListTest extends PHPUnit_Framework_TestCase
{
    /** @var  EntityList */
    private $entityList;

    const SOME_PATH = 'some path';

    public function setUp()
    {
        $this->entityList = $entityList = new EntityList(self::SOME_PATH);
    }

    public function testThatEditLinkIsSetCorrectly()
    {
        $this->assertEquals(
            array(
                array(
                    'text' => 'Edit',
                    'link' => self::SOME_PATH
                )
            ),
            $this->entityList->getActions()
        );
    }

    public function testThatJavaScriptDependenciesDefinedCorrectly()
    {
        $this->assertEquals(array('entityList'), $this->entityList->getJavaScripts());
    }
}