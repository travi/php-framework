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
require_once __DIR__ . '/../../../../../vendor/autoload.php';
require_once __DIR__ . '/../../../../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';
require_once __DIR__ . '/../../../../../vendor/phpunit/phpunit/src/Framework/Assert.php';

use travi\framework\utilities\FileSystem;

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
        set_include_path(
            get_include_path() . PATH_SEPARATOR .
            __DIR__ . '/../../../../../php/thirdparty/PHP-Dependency/library/'
        );

        $this->useContext('authentication', new FormContext());
        $this->useContext('dependencies', new DependenciesContext());
    }

    /**
     * @Given /^the file "([^"]*)" exists in "([^"]*)"$/
     */
    public function theFileNewfileJsExistsInHomeTraviSandboxResourcesTraviOrgOptimizedJs($fileName, $directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        touch($directory . $fileName);

        $this->fileName = $fileName;
        $this->directory = $directory;
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
        assertEquals($fileContents, trim($this->fileContents));
    }
}
