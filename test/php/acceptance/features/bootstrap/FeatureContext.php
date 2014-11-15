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
        $this->useContext('files', new FilesContext());
    }
}
