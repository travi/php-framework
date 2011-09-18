<?php
require_once dirname(__FILE__).'/ClientDependencies.class.php';
require_once dirname(__FILE__).'/../utilities/FileSystem.php';

class DependencyManager
{
    const SITE_THEME_KEY = 'siteTheme';
    const THIS_PAGE_KEY = 'thisPage';
    /** @var \ClientDependencies */
    private $clientDependencyDefinitions;
    private $requirementLists = array();

    /** @var FileSystem */
    private $fileSystem;

    private $pageDependenciesLists = array();

    const RESOURCES = '/resources';
    const SHARED_RESOURCES = '/resources/shared';

    public function addDependency($dep, $category, $index)
    {
        if ($category === 'js' || $category === 'JavaScript' || $category === 'javascript') {
            $this->addJavaScript($dep);
        } else if ($category === 'css' || $category === 'StyleSheet' || $category === 'stylesheet') {
            $this->addStyleSheet($dep, $index);
        } else if ($category === 'jsInit' || $category === 'jsinit') {
            $this->addJsInit($dep);
        } elseif ($category === 'validations') {
            $this->addValidations($dep, $index);
        }
    }

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

        $resolved = $this->clientDependencyDefinitions->resolveFileURI($sheet);

        if (!empty($resolved)) {
            $sheet = $resolved;
        }

        $styleSheetList = &$this->requirementLists['css'];

        if (!in_array($sheet, $styleSheetList) || $index === self::THIS_PAGE_KEY) {
            if (!empty($index)) {
                if ($index === self::THIS_PAGE_KEY && in_array($sheet, $styleSheetList)) {
                    $indexFound = array_search($sheet, $styleSheetList);
                    unset($styleSheetList[$indexFound]);
                }
                $styleSheetList[$index] = $sheet;
            } else {
                array_push($styleSheetList, $sheet);
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

    public function getDependencies($category="")
    {
        if ($category === 'js' || $category === 'JavaScript' || $category === 'javascript') {
            return $this->getScripts();
        } elseif ($category === 'css' || $category === 'StyleSheet' || $category === 'stylesheet') {
            return $this->getStyleSheets();
        } elseif ($category === 'jsInit' || $category === 'jsinit') {
            return $this->getJsInits();
        } elseif ($category === 'validations') {
            return $this->getValidations();
        } elseif ($category === 'clientTemplates') {
            return $this->getClientTemplates();
        } else {
            $this->sortStyleSheets();
            return $this->requirementLists;
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

    private function sortStyleSheets()
    {
        uksort($this->requirementLists['css'], 'strnatcasecmp');
    }

    public function getClientTemplates()
    {
        return $this->requirementLists['clientTemplates'];
    }

    public function getJsInits()
    {
        return $this->requirementLists['jsInit'];
    }

    public function getValidations()
    {
        return $this->requirementLists['validations'];
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
                    if (strpos($dependency, self::RESOURCES) === 0) {
                        if (strpos($dependency, self::SHARED_RESOURCES) === 0) {
                            $length = strlen(self::SHARED_RESOURCES);
                            $pathToDependency = INCLUDE_PATH . 'client'
                                                . substr($dependency, $length);
                        } else {
                            $pathToDependency = SITE_ROOT . 'doc_root' . $dependency;
                        }

                        if (file_exists($pathToDependency)) {
                            $this->requirementLists[$key][$index] .= '?' . md5(filemtime($pathToDependency));
                        }
                    }
                };
            }
        }
    }

    private function lazyInitializeList($category)
    {
        if (!isset($this->requirementLists[$category])) {
            $this->requirementLists[$category] = array();
        }
    }

    public function getPageStyle()
    {
        return $this->requirementLists['css'][self::THIS_PAGE_KEY];
    }

    public function loadPageDependencies($controller, $action)
    {
        $thisPage = $this->pageDependenciesLists[strtolower($controller)][$action];

        $this->addDependencies($this->pageDependenciesLists['site']);
        $this->addDependencies($thisPage);
        $this->setPageStyle($thisPage['pageStyle']);
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

    /**
     * @PdInject new:ClientDependencies
     * @param \ClientDependencies $clientDependencyDefinitions
     */
    public function setClientDependencyDefinitions($clientDependencyDefinitions)
    {
        $this->clientDependencyDefinitions = $clientDependencyDefinitions;
    }

    public function setSiteTheme($sheet)
    {
        $this->addStyleSheet($sheet, self::SITE_THEME_KEY);
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
}
