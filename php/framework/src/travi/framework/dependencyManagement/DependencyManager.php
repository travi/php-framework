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

    /** @var ClientDependencies */
    private $clientDependencyDefinitions;
    private $requirementLists = array(
        'js' => array(),
        'clientTemplates' => array()
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
    const SHARED_RESOURCES = '/resources/thirdparty/travi-styles';
    const MIN_DIR          = '/min';
    const THIRDPARTY       = 'thirdparty';

    public function addJavaScript($script)
    {
        if (!is_array($script)) {
            $this->addScriptAndItsDependencies($script);
        }
    }

    public function addStyleSheet($sheet, $index = "")
    {
        $this->lazyInitializeList('css');

        $sheet = $this->resolveFileUri($sheet);

        if ($this->addingThisStylesheetShouldAdjustExistingList($sheet, $index)) {
            $this->addStylesheetToList($sheet, $index);
        }
    }

    public function addClientTemplate($name, $template)
    {
        $this->lazyInitializeList('clientTemplates');

        if (!in_array($name, $this->requirementLists['clientTemplates'])) {
            $this->requirementLists['clientTemplates'][$name] = $template;
        }
    }

    public function addJsInit($init)
    {
        $this->lazyInitializeList('jsInit');

        array_push($this->requirementLists['jsInit'], $init);
    }

    public function addValidations($list, $form)
    {
        $validations = array();

        $this->lazyInitializeList('validations');

        foreach ($list as $field => $rules) {
            if (!empty($rules)) {
                $validations[$field] = $rules;
            }
        }

        $this->requirementLists['validations'][$form] = $validations;
    }

    public function getDependencies()
    {
        $this->sortStyleSheets();

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
        return $this->requirementLists['js'];
    }

    public function getStyleSheets()
    {
        $this->sortStyleSheets();

        return $this->requirementLists['css'];
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
        if (!empty($dependencies['js'])) {
            foreach ($dependencies['js'] as $script) {
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
        if (!empty($dependencies['css'])) {
            foreach ($dependencies['css'] as $style) {
                $this->addStyleSheet($style);
            }
        }
        if (!empty($dependencies['validations'])) {
            /** @var $component Field */
            $this->addValidations($dependencies['validations'], $component->getName());
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
            if ($key === 'css' || $key === 'js') {
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
        $this->lazyInitializeList('css');

        uksort($this->requirementLists['css'], 'strnatcasecmp');
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

    private function lazyInitializeList($category)
    {
        if (!isset($this->requirementLists[$category])) {
            $this->requirementLists[$category] = array();
        }
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
        if ($this->isThirdpartyDependency($dependency)) {
            return preg_replace(
                '/\/(' . self::THIRDPARTY . ')\//',
                self::MIN_DIR . '/$1/',
                $dependency,
                1
            );
        } else {
            return preg_replace(
                '/\/(resources.*)\/(css|js)\//',
                '/$1' . self::MIN_DIR . '/$2/',
                $dependency,
                1
            );
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
        if (isset($this->requirementLists['css'])) {
            $cssLists = $this->requirementLists['css'];
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

    public function setSiteTheme($sheet)
    {
        $this->addStyleSheet($sheet, self::SITE_THEME_KEY);
    }

    public function getDependenciesInProperForm()
    {
        $dependencies = $this->getDependencies();

        $dependencies['criticalJs'] = array('/resources/thirdparty/travi-core/thirdparty/modernizr.js');

        if ($this->shouldUseBuiltVersion()) {
            $dependencies = $this->minify($dependencies, 'css');
            $dependencies = $this->minify($dependencies, 'js');
            $dependencies = $this->minify($dependencies, 'criticalJs');
        }

        return $dependencies;
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
        if (!empty($dependencies['clientTemplates'])) {
            foreach ($dependencies['clientTemplates'] as $name => $dependency) {
                $this->addClientTemplate($name, $dependency);
            }
        }
    }

    /**
     * @param $script
     */
    private function addAsDependency($script)
    {
        if (!empty($script)) {
            array_push($this->requirementLists['js'], $script);
        }
    }

    /**
     * @param $script
     */
    private function addScriptAndItsDependencies($script)
    {
        $fileURI = $this->clientDependencyDefinitions->resolveFileURI($script);

        if ($this->hasNotAlreadyBeenAddedToDependencyListFor($fileURI, 'js')) {
            $dependencies = $this->clientDependencyDefinitions->getDependenciesFor($script);

            if (!empty($dependencies)) {
                $this->mapDependencies($dependencies);

                $script = $this->clientDependencyDefinitions->resolveFileURI($script);
            }

            $this->addAsDependency($script);
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
        $styleSheetList = &$this->requirementLists['css'];
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
        return $this->hasNotAlreadyBeenAddedToDependencyListFor($sheet, 'css')
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
     * @param $dependency
     * @return int
     */
    private function isThirdpartyDependency($dependency)
    {
        return strpos($dependency, self::THIRDPARTY);
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
}
