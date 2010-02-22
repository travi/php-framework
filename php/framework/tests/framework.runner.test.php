<?php

require_once(dirname(__FILE__) . '/../../thirdparty/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/../../thirdparty/simpletest/extensions/junit_xml_reporter.php');
//require_once(dirname(__FILE__) . '/form.runner.test.php');

class BigTestSuite extends TestSuite {
    function BigTestSuite() {
        $this->TestSuite('Framework Tests');
        $this->addFile(dirname(__FILE__) . '/form.runner.test.php');
    }
}

global $argv;
$junit = false;
$output = '/home/travi/include/build/simpletest.xml';

foreach ($argv as $i => $arg) {
	if (preg_match('/^--?(junit)$/', $arg)) {
		$junit = true;
	}
}
    
$test = &new BigTestSuite();
if($junit) {
	ob_start();
		$test->run(new JUnitXMLReporter());
		$xml = ob_get_contents();
	ob_end_clean();
	
	$outputFile = fopen($output, "w+");
	fputs($outputFile, $xml);
	fclose($outputFile);
} else {	
	$test->run(new DefaultReporter());
}
?>