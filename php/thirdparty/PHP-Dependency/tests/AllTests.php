<?php

// add lib to global include line
set_include_path(get_include_path() . PATH_SEPARATOR .
        dirname(__FILE__) . '/../library/'
);

require_once 'PHPUnit/Framework.php';

require_once 'MapTests/ItemTest.php';
require_once 'MapTests/MapTest.php';

require_once 'MapTests/BuilderTests/ParserTest.php';
require_once 'MapTests/BuilderTests/ClassTest.php';
require_once 'MapTests/BuilderTests/ArrayTest.php';

require_once 'ContainerTests/ContainerTest.php';
require_once 'ContainerTests/DependenciesTest.php';
require_once 'ContainerTests/MapsTest.php';

require_once 'MakeTests/ConstructorTest.php';
require_once 'MakeTests/SetterTest.php';
require_once 'MakeTests/MakeTest.php';

class PdTests_AllTests extends PHPUnit_Framework_TestSuite {

    protected function setUp() {
        
    }

    public static function suite() {

        $suite = new PdTests_AllTests();

        $suite->addTestSuite('PdTests_MapTests_ItemTest');
        $suite->addTestSuite('PdTests_MapTests_MapTest');

        $suite->addTestSuite('PdTests_MapTests_BuilderTests_ParserTest');
        $suite->addTestSuite('PdTests_MapTests_BuilderTests_ClassTest');
        $suite->addTestSuite('PdTests_MapTests_BuilderTests_ArrayTest');

        $suite->addTestSuite('PdTests_ContainerTests_ContainerTest');
        $suite->addTestSuite('PdTests_ContainerTests_DependenciesTest');
        $suite->addTestSuite('PdTests_ContainerTests_MapsTest');

        $suite->addTestSuite('PdTests_MakeTests_ConstructorTest');
        $suite->addTestSuite('PdTests_MakeTests_SetterTest');
        $suite->addTestSuite('PdTests_MakeTests_MakeTest');

        return $suite;
    }


}
