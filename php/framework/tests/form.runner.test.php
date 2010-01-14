<?php

require_once(dirname(__FILE__) . '/../../simpletest/autorun.php');

class FormTestSuite extends TestSuite {
    function FormTestSuite() {
        $this->TestSuite('Form tests');
        $this->addTestFile(dirname(__FILE__) . '/helloWorld.test.php');
    }
}
?>