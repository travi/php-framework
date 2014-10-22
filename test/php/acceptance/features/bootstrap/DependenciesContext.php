<?php

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use travi\framework\controller\front\FrontController;
use travi\framework\dependencyManagement\DependencyManager;
use travi\framework\http\Session;

class DependenciesContext extends BehatContext
{
    /** @var  Pd_Container */
    private $container;
    /** @var  FrontController */
    private $frontController;
    /** @var  DependencyManager */
    private $dependencyManager;

    /**
     * @BeforeScenario
     */
    public function initializeApplication()
    {
        if (!defined('SITE_ROOT')) {
            define('SITE_ROOT', __DIR__ . '/../../../../../php/framework/test/php/mockProject/');
        }

        $_SERVER['HTTP_HOST'] = '';

        $this->container = Pd_Container::get();

        $containerDependencies = $this->container->dependencies();

        $uiDeps = Spyc::YAMLLoad(__DIR__ . '/../../../../../config/uiDependencies.yaml');
        $containerDependencies->set('uiDeps', $uiDeps);

        $containerDependencies->set('uri', '/');
        $containerDependencies->set('session', Pd_Make::name('SessionShunt'));
        $containerDependencies->set('request', Pd_Make::name('travi\\framework\\http\\Request'));

        /** @var Smarty $smarty */
        $smarty = Pd_Make::name('SmartyShunt');
        $containerDependencies->set('Smarty', $smarty);

        $fileSystem = Pd_Make::name('travi\\framework\\utilities\\FileSystem');
        $containerDependencies->set('fileSystem', $fileSystem);

        $environment = Pd_Make::name('travi\\framework\\utilities\\Environment');
        $containerDependencies->set('environment', $environment);

        $this->dependencyManager = Pd_Make::name('travi\\framework\\dependencyManagement\\DependencyManager');
        $containerDependencies->set('dependencyManager', $this->dependencyManager);

        $containerDependencies->set(
            'htmlRenderer',
            Pd_Make::name('travi\\framework\\view\\render\\HtmlRenderer')
        );
        $containerDependencies->set('response', Pd_Make::name('travi\\framework\\http\\Response'));

        $this->frontController = Pd_Make::name('travi\\framework\\controller\\front\\FrontController');
    }

    /**
     * @Given /^no dependencies are defined$/
     */
    public function noDependenciesAreDefined()
    {

    }

    /**
     * @Given /^"([^"]*)" defined as a dependency$/
     */
    public function definedAsADependency($dependency)
    {
        $this->dependencyManager->addJavaScript($dependency);
    }

    /**
     * @Given /^environment is "([^"]*)"$/
     */
    public function environmentIs($environment)
    {
        if ('local' === $environment) {
            $_SERVER['HTTP_HOST'] = 'mock.dev';
        }
    }

    /**
     * @When /^page is rendered$/
     */
    public function pageHasBeenRequested()
    {
        $this->frontController->processRequest();
    }

    /**
     * @Then /^the dependencies lists should contain$/
     */
    public function theDependenciesListsShouldContain(TableNode $table)
    {
        list($css, $js) = $this->parseDependencyListsFrom($table);

        /** @var Smarty $smarty */
        $smarty = $this->container->dependencies()->get('Smarty');

        $dependencies = $smarty->getVariable('dependencies')->value;

        assertEquals($css, $dependencies['css']);
        assertEquals($js, $dependencies['js']);
    }

    /**
     * @param $list
     * @param $entry
     */
    private function addEntryTo(&$list, $entry)
    {
        if (!empty($entry)) {
            array_push($list, $entry);
        }
    }

    /**
     * @param TableNode $table
     * @return array
     */
    private function parseDependencyListsFrom(TableNode $table)
    {
        $hash = $table->getHash();
        $css = array();
        $js = array();

        foreach ($hash as $row) {
            $this->addEntryTo($css, $row['css']);
            $this->addEntryTo($js, $row['js']);
        }

        return array($css, $js);
    }
}

class SessionShunt extends Session
{
    public function __construct()
    {

    }
}

class SmartyShunt extends Smarty
{
    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {

    }

}