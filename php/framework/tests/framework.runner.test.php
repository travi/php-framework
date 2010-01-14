<?php

require_once(dirname(__FILE__) . '/../../simpletest/autorun.php');
//require_once(dirname(__FILE__) . '/form.runner.test.php');

class BigTestSuite extends TestSuite {
    function BigTestSuite() {
        $this->TestSuite('Framework tests');
        $this->addTestFile(dirname(__FILE__) . '/form.runner.test.php');
    }
}
    
$test = &new BigTestSuite();
$test->run(new DefaultReporter());

//if ($test->getBaseTestCase()) {
//	exit(81);
//}
?>