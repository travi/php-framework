<?php

use Behat\Behat\Context\BehatContext;
use Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\TableNode;
use travi\framework\controller\front\FrontController;
use travi\framework\dependencyManagement\DependencyManager;
use travi\framework\http\Request;
use travi\framework\http\Session;
use travi\framework\utilities\FileSystem;

class DependenciesContext extends BehatContext
{
    /** @var  Request */
    private $request;
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
        $this->request = Pd_Make::name('travi\\framework\\http\\Request');
        $containerDependencies->set('request', $this->request);

        /** @var Smarty $smarty */
        $smarty = Pd_Make::name('SmartyShunt');
        $containerDependencies->set('Smarty', $smarty);

        /** @var FileSystem $fileSystem */
        $fileSystem = Pd_Make::name('travi\\framework\\utilities\\FileSystem');
        $fileSystem->setSitePath(SITE_ROOT);
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
     * @AfterScenario
     */
    public function unsetScreenSizeCookie()
    {
        $_COOKIE = array();
        $this->request->setEnhancementVersion(Request::BASE_ENHANCEMENT);
    }

    /**
     * @Given /^no dependencies are defined$/
     */
    public function noDependenciesAreDefined()
    {

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
     * @Given /^device has a "([^"]*)" screen size$/
     */
    public function deviceHasAScreenSize($screenSize)
    {
        if ("small" === $screenSize) {
            $_COOKIE['ev'] = 'm';
            $this->request->setEnhancementVersion(Request::SMALL_COOKIE_VALUE);
        } elseif ("large" === $screenSize) {
            $_COOKIE['ev'] = 'l';
            $this->request->setEnhancementVersion(Request::LARGE_COOKIE_VALUE);
        }
    }

    /**
     * @Given /^"([^"]*)" defined as a dependency$/
     */
    public function definedAsADependency($dependency)
    {
        $this->dependencyManager->addJavaScript($dependency);
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
        list($css, $js, $templates) = $this->parseDependencyListsFrom($table);

        $dependencies = $this->getActualDependencies();

        assertEquals($js, $dependencies['js']);
        assertEquals($css, $dependencies['css']);
        assertEquals($templates, array_values($dependencies['clientTemplates']));
    }

    /**
     * @Then /^the critical list should contain$/
     */
    public function theCriticalListShouldContain(TableNode $table)
    {

        $hash = $table->getHash();
        $criticalJs = array();

        foreach ($hash as $row) {
            $this->addEntryTo($criticalJs, $row['js']);
        }

        $dependencies = $this->getActualDependencies();

        assertEquals($criticalJs, $dependencies['criticalJs']);
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
        $templates = array();

        foreach ($hash as $row) {
            $this->addEntryTo($css, $row['css']);
            $this->addEntryTo($js, $row['js']);
            $this->addEntryTo($templates, $row['templates']);
        }

        return array($css, $js, $templates);
    }

    /**
     * @return mixed
     */
    private function getActualDependencies()
    {
        /** @var Smarty $smarty */
        $smarty = $this->container->dependencies()->get('Smarty');

        $dependencies = $smarty->getVariable('dependencies')->value;
        return $dependencies;
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