<?php

require_once(dirname(__FILE__) . '/../../simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../../simpletest/extensions/junit_xml_reporter.php');
//require_once(dirname(__FILE__) . '/form.runner.test.php');

class BigTestSuite extends TestSuite {
    function BigTestSuite() {
        $this->TestSuite('Framework tests');
        $this->addTestFile(dirname(__FILE__) . '/form.runner.test.php');
    }
}

global $argv;
$junit = false;

foreach ($argv as $i => $arg) {
	if (preg_match('/^--?(junit)$/', $arg)) {
		$junit = true;
	}
}
    
$test = &new BigTestSuite();
if($junit) {
	$test->run(new JUnitXMLReporter('/home/travi/include/build/simpletest.xml'));
} else {	
	$test->run(new DefaultReporter());
}
?>