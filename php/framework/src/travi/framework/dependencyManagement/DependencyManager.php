<?php

namespace travi\framework\dependencyManagement;

use travi\framework\utilities\FileSystem,
    travi\framework\http\Request,
    travi\framework\utilities\Environment,
    travi\framework\http\Session,
    travi\framework\components\Forms\Field,
    travi\framework\content\ContentObject;

class DependencyManager
{
    const SITE_THEME_KEY          = 'siteTheme';
    const SITE_THEME_ENHANCED_KEY = 'siteThemeEnhanced';
    const THIS_PAGE_KEY           = 'thisPage';
    const THIS_PAGE_ENHANCED_KEY  = 'thisPageEnhanced';
    
    const JS_LIST = 'js';
    const TEMPLATE_LIST = 'clientTemplates';
    const VALIDATION_LIST = 'validations';
    const CSS_LIST = 'css';
    const JS_INIT_LIST = 'jsInit';

    /** @var ClientDependencies */
    private $clientDependencyDefinitions;
    private $requirementLists = array(
        self::CSS_LIST => array(),
        self::JS_LIST => array(),
        self::TEMPLATE_LIST => array(),
        self::VALIDATION_LIST => array(),
        self::JS_INIT_LIST => array()
    );

    /** @var FileSystem */
    private $fileSystem;
    /** @var Environment */
    private $environment;
    /** @var Request */
    private $request;
    /** @var Session */
    private $session;

    private $pageDependenciesLists = array();

    const RESOURCES        = '/resources';
    const SHARED_RESOURCES = '/resources/shared';
    const MIN_DIR          = '/min';

    public function addJavaScript($script)
    {
        if (!is_array($script)) {
            $this->addScriptAndItsDependencies($script);
        }
    }

    public function addStyleSheet($sheet, $index = "")
    {
        $sheet = $this->resolveFileUri($sheet);

        if ($this->addingThisStylesheetShouldAdjustExistingList($sheet, $index)) {
            $this->addStylesheetToList($sheet, $index);
        }
    }

    public function addClientTemplate($name, $template)
    {
        if (!in_array($name, $this->requirementLists[self::TEMPLATE_LIST])) {
            $this->requirementLists[self::TEMPLATE_LIST][$name] = $template;
        }
    }

    public function addJsInit($init)
    {
        array_push($this->requirementLists[self::JS_INIT_LIST], $init);
    }

    public function addValidations($list, $form)
    {
        $validations = array();

        foreach ($list as $field => $rules) {
            if (!empty($rules)) {
                $validations[$field] = $rules;
            }
        }

        $this->requirementLists[self::VALIDATION_LIST][$form] = $validations;
    }

    public function getDependencies()
    {
        $this->requirementLists[self::CSS_LIST] = $this->getStyleSheets();

        return $this->requirementLists;
    }

    public function loadPageDependencies()
    {
        $controllerList = $this->getListOfControllers();

        $controllerName = strtolower($this->request->getController());
        if ($this->controllerIsIn($controllerList, $controllerName)) {
            $thisPage = $this->getDependenciesForThisPage($controllerList, $controllerName);

            $this->addDependencies($thisPage);
            $this->setPageStyleFrom($thisPage);
        } else {
            $this->setPageStyle();
        }
    }

    public function getScripts()
    {
        return $this->requirementLists[self::JS_LIST];
    }

    public function getStyleSheets()
    {
        return $this->sortStyleSheets();
    }

    public function addDependencies($dependencies = array(), $component = null)
    {
        if ($this->environment->isProduction() && !empty($dependencies['production'])) {
            $this->addDependencies($dependencies['production']);
        }
        if (!empty($dependencies['scripts'])) {
            foreach ($dependencies['scripts'] as $script) {
                $this->addJavaScript($script);
            }
        }
        if (!empty($dependencies[self::JS_LIST])) {
            foreach ($dependencies[self::JS_LIST] as $script) {
                $this->addJavaScript($script);
            }
        }
        if (!empty($dependencies['jsInits'])) {
            foreach ($dependencies['jsInits'] as $init) {
                $this->addJsInit($init);
            }
        }
        if (!empty($dependencies['styles'])) {
            foreach ($dependencies['styles'] as $style) {
                $this->addStyleSheet($style);
            }
        }
        if (!empty($dependencies[self::CSS_LIST])) {
            foreach ($dependencies[self::CSS_LIST] as $style) {
                $this->addStyleSheet($style);
            }
        }
        if (!empty($dependencies[self::VALIDATION_LIST])) {
            /** @var $component Field */
            $this->addValidations($dependencies[self::VALIDATION_LIST], $component->getName());
        }
        if (!empty($dependencies[$this->request->getEnhancementVersion()])) {
            $this->addDependencies($dependencies[$this->request->getEnhancementVersion()]);
        }
    }

    /**
     * @param  $component ContentObject
     * @return void
     */
    public function resolveComponentDependencies($component)
    {
        if (is_object($component) && is_a($component, 'travi\\framework\\DependantObject')) {
            $this->addDependencies($component->getDependencies(), $component);
        } else if (is_array($component)) { //TODO: need to make this DRY
            foreach ($component as $innerComponent) {
                $this->resolveComponentDependencies($innerComponent);
            }
        }
    }

    public function resolveContentDependencies($content)
    {
        if (is_array($content)) {
            foreach ($content as $component) {
                $this->resolveComponentDependencies($component);
            }
        } else {
            $this->resolveComponentDependencies($content);
        }
    }

    public function addCacheBusters()
    {
        foreach ($this->requirementLists as $key => $list) {
            if ($key === self::CSS_LIST || $key === self::JS_LIST) {
                foreach ($list as $index => $dependency) {
                    if ($this->isLocalFile($dependency)) {
                        $this->addCacheBusterIfFileExists($dependency, $key, $index);
                    }
                };
            }
        }
    }

    private function sortStyleSheets()
    {
        $css = $this->requirementLists[self::CSS_LIST];

        uksort($css, 'strnatcasecmp');

        return $css;
    }

    private function addCacheBusterIfFileExists($dependency, $key, $index)
    {
        $pathToDependency = $this->buildPathToDependency($dependency);

        if ($this->fileSystem->fileExists($pathToDependency)) {
            $this->requirementLists[$key][$index] .= '?' . md5(
                $this->fileSystem->getLastModifiedTimeFor($pathToDependency)
            );
        }
    }

    private function buildPathToDependency($dependency)
    {
        if ($this->isSharedDependency($dependency)) {
            $length = strlen(self::SHARED_RESOURCES);

            return $this->fileSystem->getSharedPath() . '/client' . substr($dependency, $length);
        } else {
            return SITE_ROOT . 'doc_root' . $dependency;
        }
    }

    private function isSharedDependency($dependency)
    {
        return strpos($dependency, self::SHARED_RESOURCES) === 0;
    }

    private function isLocalFile($dependency)
    {
        return strpos($dependency, self::RESOURCES) === 0;
    }

    private function minify($dependencies, $list)
    {
        foreach ($dependencies[$list] as &$dependency) {
            $dependency = $this->replaceWithMinifiedVersion($dependency);
        }

        return $dependencies;
    }

    private function replaceWithMinifiedVersion($dependency)
    {
        if ($this->containedInDist($dependency)) {
            return preg_replace(
                '/\/(resources)\/(css|js|thirdparty)\//',
                '/$1' . self::MIN_DIR . '/$2/',
                $dependency,
                1
            );
        } else {
            return $dependency;
        }
    }

    private function resolveFileUri($sheet)
    {
        $resolved = $this->clientDependencyDefinitions->resolveFileURI($sheet);

        if (!empty($resolved)) {
            $sheet = $resolved;

            return $sheet;
        }

        return $sheet;
    }

    private function getEnhancedFileName($sheet)
    {
        $enhancementVersion = $this->request->getEnhancementVersion();

        if ($enhancementVersion === Request::SMALL_ENHANCEMENT) {
            return substr($sheet, 0, strpos($sheet, '.css')) . '_m.css';
        } elseif ($enhancementVersion === Request::LARGE_ENHANCEMENT) {
            return substr($sheet, 0, strpos($sheet, '.css')) . '_d.css';
        }

        return '';
    }

    private function removePageStyleIfAlreadyInList($index, $sheet, $styleSheetList)
    {
        if ($index === self::THIS_PAGE_KEY && in_array($sheet, $styleSheetList)) {
            $indexFound = array_search($sheet, $styleSheetList);
            unset($styleSheetList[$indexFound]);
        }
    }

    public function getPageStyle()
    {
        if (isset($this->requirementLists[self::CSS_LIST])) {
            $cssLists = $this->requirementLists[self::CSS_LIST];
        }

        if (isset($cssLists[self::THIS_PAGE_KEY])) {
            return $cssLists[self::THIS_PAGE_KEY];
        }
    }

    public function setPageStyle($thisPageStyle = '')
    {
        $currentPageStyle = $this->getPageStyle();

        if (!empty($thisPageStyle)) {
            $this->addStyleSheet($thisPageStyle, self::THIS_PAGE_KEY);
        } elseif (empty($currentPageStyle)) {
            $pageStyleByConvention = $this->fileSystem->getPageStyleByConvention();

            if ($pageStyleByConvention) {
                $this->setPageStyle($pageStyleByConvention);
            }
        }
    }

    public function setPageDependenciesLists($lists)
    {
        $this->pageDependenciesLists = $lists;
        if (isset($this->pageDependenciesLists['site'])) {
            $this->addDependencies($this->pageDependenciesLists['site']);
        }
    }

    public function getDependenciesInProperForm()
    {
        $dependencies = $this->getDependencies();

        $dependencies['criticalJs'] = $this->populateCriticalJs();
        $dependencies = $this->minifyAll($dependencies);

        return $dependencies;
    }

    public function setSiteTheme($sheet)
    {
        $this->addStyleSheet($sheet, self::SITE_THEME_KEY);
    }

    /**
     * @param $fileURI
     * @param $dependencyType
     * @return bool
     */
    private function hasNotAlreadyBeenAddedToDependencyListFor($fileURI, $dependencyType)
    {
        return !in_array($fileURI, $this->requirementLists[$dependencyType]);
    }

    /**
     * @param $dependencies
     */
    private function mapDependencies($dependencies)
    {
        if (!empty($dependencies['jsDependencies'])) {
            foreach ($dependencies['jsDependencies'] as $dependency) {
                $this->addJavaScript($dependency);
            }
        }
        if (!empty($dependencies['cssDependencies'])) {
            foreach ($dependencies['cssDependencies'] as $dependency) {
                $this->addStyleSheet($dependency);
            }
        }
        if (!empty($dependencies[self::TEMPLATE_LIST])) {
            foreach ($dependencies[self::TEMPLATE_LIST] as $name => $dependency) {
                $this->addClientTemplate($name, $dependency);
            }
        }
    }

    /**
     * @param $script
     */
    private function addAsScriptDependency($script)
    {
        if (!empty($script)) {
            array_push($this->requirementLists[self::JS_LIST], $script);
        }
    }

    /**
     * @param $script
     */
    private function addScriptAndItsDependencies($script)
    {
        $fileURI = $this->clientDependencyDefinitions->resolveFileURI($script);

        if ($this->hasNotAlreadyBeenAddedToDependencyListFor($fileURI, self::JS_LIST)) {
            $dependencies = $this->clientDependencyDefinitions->getDependenciesFor($script);

            if (!empty($dependencies)) {
                $this->mapDependencies($dependencies);

                $script = $this->clientDependencyDefinitions->resolveFileURI($script);
            }

            $this->addAsScriptDependency($script);
        }
    }

    /**
     * @param $index
     * @return bool
     */
    private function isStylesheetForThisPage($index)
    {
        return $index === self::THIS_PAGE_KEY;
    }

    /**
     * @param $index
     * @return bool
     */
    private function stylesheetShouldBeRankedBy($index)
    {
        return !empty($index);
    }

    /**
     * @param $sheet
     * @param $index
     * @param $styleSheetList
     * @return mixed
     */
    private function addStylesheetAt($sheet, $index, &$styleSheetList)
    {
        if ($this->fileSystem->styleSheetExists($sheet)) {
            $styleSheetList[$index] = $sheet;
            return $styleSheetList;
        }
    }

    /**
     * @param $index
     * @param $enhancedFile
     * @param $styleSheetList
     */
    private function addEnhancementStylesheetAt($index, $enhancedFile, &$styleSheetList)
    {
        if (!empty($enhancedFile) && $this->fileSystem->styleSheetExists($enhancedFile)) {
            $styleSheetList[$index . 'Enhanced'] = $enhancedFile;
        }
    }

    /**
     * @param $enhancedFile
     * @param $styleSheetList
     */
    private function addEnhancementStylesheet($enhancedFile, &$styleSheetList)
    {
        if (!empty($enhancedFile) && $this->fileSystem->styleSheetExists($enhancedFile)) {
            array_push($styleSheetList, $enhancedFile);
        }
    }

    /**
     * @param $sheet
     * @param $index
     * @param $styleSheetList
     * @param $enhancedFile
     */
    private function placeStylesheetAtProperRank($sheet, $index, &$styleSheetList, $enhancedFile)
    {
        $this->removePageStyleIfAlreadyInList($index, $sheet, $styleSheetList);
        $this->addStylesheetAt($sheet, $index, $styleSheetList);
        $this->addEnhancementStylesheetAt($index, $enhancedFile, $styleSheetList);
    }

    /**
     * @param $sheet
     * @param $enhancedFile
     * @param $styleSheetList
     */
    private function addStylesheetAtEndOfList($sheet, $enhancedFile, &$styleSheetList)
    {
        if ($this->fileSystem->styleSheetExists($sheet) || !$this->isLocalFile($sheet)) {
            array_push($styleSheetList, $sheet);
        }
        $this->addEnhancementStylesheet($enhancedFile, $styleSheetList);
    }

    /**
     * @param $sheet
     * @param $index
     * @internal param $styleSheetList
     */
    private function addStylesheetToList($sheet, $index)
    {
        $styleSheetList = &$this->requirementLists[self::CSS_LIST];
        $enhancedFile = $this->getEnhancedFileName($sheet);

        if ($this->stylesheetShouldBeRankedBy($index)) {
            $this->placeStylesheetAtProperRank($sheet, $index, $styleSheetList, $enhancedFile);
        } else {
            $this->addStylesheetAtEndOfList($sheet, $enhancedFile, $styleSheetList);
        }
    }

    /**
     * @param $sheet
     * @param $index
     * @return bool
     */
    private function addingThisStylesheetShouldAdjustExistingList($sheet, $index)
    {
        return $this->hasNotAlreadyBeenAddedToDependencyListFor($sheet, self::CSS_LIST)
            || $this->isStylesheetForThisPage($index);
    }

    /**
     * @return array
     */
    private function getListOfControllers()
    {
        if ($this->request->isAdmin()) {
            $controllerList = $this->pageDependenciesLists['admin'];
            return $controllerList;
        } else {
            $controllerList = $this->pageDependenciesLists;
            return $controllerList;
        }
    }

    /**
     * @param $controllerList
     * @param $controllerName
     * @return bool
     */
    private function controllerIsIn($controllerList, $controllerName)
    {
        return isset($controllerList[$controllerName]);
    }

    /**
     * @param $thisPage
     */
    private function setPageStyleFrom($thisPage)
    {
        if (isset($thisPage['pageStyle'])) {
            $thisPageStyle = $thisPage['pageStyle'];
        } else {
            $thisPageStyle = null;
        }
        $this->setPageStyle($thisPageStyle);
    }

    /**
     * @param $controllerList
     * @param $controllerName
     * @return mixed
     */
    private function getDependenciesForThisPage($controllerList, $controllerName)
    {
        $thisController = $controllerList[$controllerName];
        $action = $this->request->getAction();
        $thisPage = $thisController[$action];

        return $thisPage;
    }

    /**
     * @return bool
     */
    private function shouldUseBuiltVersion()
    {
        return !$this->environment->isLocal() && !$this->session->isDebug();
    }

    /**
     * @PdInject new:travi\framework\dependencyManagement\ClientDependencies
     * @param ClientDependencies $clientDependencyDefinitions
     */
    public function setClientDependencyDefinitions($clientDependencyDefinitions)
    {
        $this->clientDependencyDefinitions = $clientDependencyDefinitions;
    }

    /**
     * @PdInject fileSystem
     * @param $fileSystem
     * @return void
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @PdInject environment
     * @param $env \travi\framework\utilities\Environment
     * @return void
     */
    public function setEnvironmentUtility($env)
    {
        $this->environment = $env;
    }

    /**
     * @PdInject request
     * @param $request Request
     * @return void
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @param $session Session
     * @PdInject session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return array
     */
    private function populateCriticalJs()
    {
        $criticalJs = array();
        array_push($criticalJs, '/resources/thirdparty/travi-core/thirdparty/modernizr.js');

        if ($this->request->getEnhancementVersion() === Request::BASE_ENHANCEMENT) {
            array_push($criticalJs, '/resources/thirdparty/travi-core/dist/travi-critical.min.js');
            return $criticalJs;
        }

        return $criticalJs;
    }

    /**
     * @param $dependencies
     * @return mixed
     */
    private function minifyAll($dependencies)
    {
        if ($this->shouldUseBuiltVersion()) {
            $dependencies = $this->minify($dependencies, self::CSS_LIST);
            $dependencies = $this->minify($dependencies, self::JS_LIST);
            $dependencies = $this->minify($dependencies, 'criticalJs');
            return $dependencies;
        }
        return $dependencies;
    }

    /**
     * @param $dependency
     * @return bool
     */
    private function containedInDist($dependency)
    {
        return false === strpos($dependency, '/dist/');
    }
}
