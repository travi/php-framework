<?php

require_once(dirname(__FILE__) . '/../../thirdparty/simpletest/autorun.php');

class FormTestSuite extends TestSuite {
    function FormTestSuite() {
        $this->TestSuite('Form tests');
        $this->addFile(dirname(__FILE__) . '/helloWorld.unit.php');
    }
}
?>