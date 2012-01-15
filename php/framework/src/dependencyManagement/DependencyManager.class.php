<?php
require_once dirname(__FILE__).'/ClientDependencies.class.php';
require_once dirname(__FILE__).'/../utilities/FileSystem.php';

class DependencyManager
{
    const SITE_THEME_KEY = 'siteTheme';
    const SITE_THEME_ENHANCED_KEY = 'siteThemeEnhanced';
    const THIS_PAGE_KEY = 'thisPage';
    const THIS_PAGE_ENHANCED_KEY = 'thisPageEnhanced';
    const MIN_DIR = '/min';

    /** @var \ClientDependencies */
    private $clientDependencyDefinitions;
    private $requirementLists = array();

    /** @var FileSystem */
    private $fileSystem;
    /** @var Environment */
    private $envUtil;
    /** @var Request */
    private $request;

    private $pageDependenciesLists = array();

    const RESOURCES = '/resources';
    const SHARED_RESOURCES = '/resources/shared';

    public function addJavaScript($script)
    {
        $this->lazyInitializeList('js');

        if (!is_array($script)) {
            $fileURI = $this->clientDependencyDefinitions->resolveFileURI($script);
            if (!in_array($fileURI, $this->requirementLists['js'])) {
                $dependencies = $this->clientDependencyDefinitions->getDependenciesFor($script);

                if (!empty($dependencies)) {
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
                    $script = $this->clientDependencyDefinitions->resolveFileURI($script);
                }
                array_push($this->requirementLists['js'], $script);
            }
        }
    }

    public function addStyleSheet($sheet, $index = "")
    {
        $this->lazyInitializeList('css');

        $sheet = $this->resolveFileUri($sheet);

        $styleSheetList = &$this->requirementLists['css'];

        if (!in_array($sheet, $styleSheetList) || $index === self::THIS_PAGE_KEY) {
            $enhancedFile = $this->getEnhancedFileName($sheet);

            if (!empty($index)) {
                $this->removePageStyleIfAlreadyInList($index, $sheet, $styleSheetList);

                if ($this->fileSystem->styleSheetExists($sheet)) {
                    $styleSheetList[$index] = $sheet;
                }
                if (!empty($enhancedFile) && $this->fileSystem->styleSheetExists($enhancedFile)) {
                    $styleSheetList[$index . 'Enhanced'] = $enhancedFile;
                }
            } else {
                if ($this->fileSystem->styleSheetExists($sheet)) {
                    array_push($styleSheetList, $sheet);
                }
                if (!empty($enhancedFile) && $this->fileSystem->styleSheetExists($enhancedFile)) {
                    array_push($styleSheetList, $enhancedFile);
                }
            }
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
        if ($this->request->isAdmin()) {
            $controllerList = $this->pageDependenciesLists['admin'];
        } else {
            $controllerList = $this->pageDependenciesLists;
        }

        $thisController = $controllerList[strtolower($this->request->getController())];
        $thisPage = $thisController[$this->request->getAction()];

        $this->addDependencies($this->pageDependenciesLists['site']);
        $this->addDependencies($thisPage);
        $this->setPageStyle($thisPage['pageStyle']);
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
        if (!empty($dependencies['production']) && ENV === 'production') {
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
        if (is_object($component) && is_a($component, 'DependantObject')) {
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
            $pathToDependency = $this->fileSystem->getSharedPath() . '/client'
                                . substr($dependency, $length);
            return $pathToDependency;
        } else {
            $pathToDependency = SITE_ROOT . 'doc_root' . $dependency;
            return $pathToDependency;
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
        return preg_replace('/\/(css|js)\//', '/min/$1/', $dependency, 1);
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
        $enhancedFile = '';
        if ($enhancementVersion === Request::MOBILE_ENHANCEMENT) {
            $enhancedFile = substr($sheet, 0, strpos($sheet, '.css')) . '_m.css';
            return $enhancedFile;
        } elseif ($enhancementVersion === Request::DESKTOP_ENHANCEMENT) {
            $enhancedFile = substr($sheet, 0, strpos($sheet, '.css')) . '_d.css';
            return $enhancedFile;
        }
        return $enhancedFile;
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
        return $this->requirementLists['css'][self::THIS_PAGE_KEY];
    }

    public function setPageStyle($thisPageStyle)
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
    }

    public function setSiteTheme($sheet)
    {
        $this->addStyleSheet($sheet, self::SITE_THEME_KEY);
    }

    public function getDependenciesInProperForm()
    {
        $dependencies = $this->getDependencies();

        if (!$this->envUtil->isLocal()) {
            $dependencies = $this->minify($dependencies, 'css');
            $dependencies = $this->minify($dependencies, 'js');
        }

        return $dependencies;
    }

    /**
     * @PdInject new:ClientDependencies
     * @param \ClientDependencies $clientDependencyDefinitions
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
     * @param $env Environment
     * @return void
     */
    public function setEnvironmentUtility($env)
    {
        $this->envUtil = $env;
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
}
