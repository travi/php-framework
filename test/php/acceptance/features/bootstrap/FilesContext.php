<?php

use Behat\Behat\Context\BehatContext;
use travi\framework\utilities\FileSystem;

class FilesContext extends BehatContext
{
    private $fileName;
    private $directory;
    private $fileContents;

    /**
     * @Given /^the file "([^"]*)" exists in "([^"]*)"$/
     * @param $fileName
     * @param $directory
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
     * @param $fileContents
     */
    public function theFrameworkShouldReceiveTheFollowingStringSomeText($fileContents)
    {
        assertEquals($fileContents, trim($this->fileContents));
    }
}