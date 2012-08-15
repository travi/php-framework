<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

//
// Require 3rd-party libraries here:
//
//   require_once 'PHPUnit/Autoload.php';
//   require_once 'PHPUnit/Framework/Assert/Functions.php';
//
require_once dirname(__FILE__) . '/../../../../../php/framework/src/utilities/FileSystem.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        // Initialize your context here
    }

    /**
     * @Given /^the file "([^"]*)" exists in "([^"]*)"$/
     */
    public function theFileNewfileJsExistsInHomeTraviSandboxResourcesTraviOrgOptimizedJs($fileName, $directory)
    {
        $this->fileName = $fileName;
        $this->directory = $directory;
        echo "this was done manually";
    }

    /**
     * @When /^the framework requests the the contents of the file$/
     */
    public function theFrameworkRequestsTheTheContentsOfTheFile()
    {
        $fileSystem = new FileSystem();
        $this->fileContents = $fileSystem->readFile($this->fileName, $this->directory);
    }

    /**
     * @Then /^the framework should receive the following string: "([^"]*)"$/
     */
    public function theFrameworkShouldReceiveTheFollowingStringSomeText($fileContents)
    {
        if ((string) $fileContents !== trim($this->fileContents)) {
            throw new Exception(
                'Actual contents are: ' . $this->fileContents
            );
        }
    }
}
