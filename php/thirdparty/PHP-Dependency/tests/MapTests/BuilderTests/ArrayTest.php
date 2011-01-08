<?php

require_once 'PHPUnit/Framework.php';

require_once 'Pd/Map/Builder/Array.php';


class PdTests_MapTests_BuilderTests_ArrayTest extends PHPUnit_Framework_TestCase {

    /**
     * @var Pd_Map_Builder_Array
     */
    private $builder;

    protected function setUp() {
        $this->builder = new Pd_Map_Builder_Array();

        $this->builder->add(array(
                'dependencyName' => 'database',
                'injectWith' => 'method',
                'injectAs' => 'setDatabase',
        ));
        $this->builder->add(array(
                'dependencyName' => 'apple',
                'injectWith' => 'constructor',
                'injectAs' => 1
        ));
        $this->builder->add(array(
                'injectWith' => 'property',
                'injectAs' => 'theService',
                'force' => true,
                'newClass' => 'Service_Class',
        ));

    }

    public function testAdd() {
        $this->builder->add(array(
                'dependencyName' => 'someDep',
                'injectWith' => 'property',
                'injectAs' => 'someDep',
        ));

        $this->builder->build();

        $this->assertEquals(4, $this->builder->map()->count());
    }

    public function testBuild() {

        $this->builder->build();

        $map = $this->builder->map();
        $item = $map->itemsFor('constructor');

        $this->assertEquals(
                'apple',
                $item[0]->dependencyName()
        );

    }



    protected function tearDown() {
        unset($this->builder);
    }


}