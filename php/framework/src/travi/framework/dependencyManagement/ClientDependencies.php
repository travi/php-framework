<?php

namespace travi\framework\dependencyManagement;

use travi\framework\http\Request,
    travi\framework\exception\MissingLocalPathToResourceException;

class ClientDependencies
{
    const DEFAULT_JQUERY_UI_THEME
        = 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.min.css';

    const CSS_DEPENDENCIES_KEY = 'cssDependencies';
    const JS_DEPENDENCIES_KEY  = 'jsDependencies';

    /** @var Request */
    private $request;
    private $uiDeps;
    private $jsNeeds = array();

    const LOCAL = 'local';

    public function getDependenciesFor($item)
    {
        $this->lazyInit();

        if (isset($this->jsNeeds[$item])) {
            return $this->jsNeeds[$item];
        }
    }

    public function resolveFileURI($resource)
    {
        $this->lazyInit();

        if (isset($this->jsNeeds[$resource]) && isset($this->jsNeeds[$resource][self::LOCAL])) {
            return $this->jsNeeds[$resource][self::LOCAL];
        } else {
            return $resource;
        }
    }

    private function flattenDeps($deps = array(), $requirement = null)
    {
        $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = $this->determineJqueryUiTheme();

        foreach ($deps as $name => $dep) {
            $this->mapConfigDetails($dep, $name, $requirement);
            $this->applyLargeScreenEnhancement($requirement, $dep, $name);
        }
    }

    private function largeScreenEnhancementsDefined($dep)
    {
        return !empty($dep[Request::LARGE_ENHANCEMENT]);
    }

    private function largeScreenVersionRequested()
    {
        return $this->request->getEnhancementVersion() === Request::LARGE_ENHANCEMENT;
    }

    private function mapConfigDetails($dep, $name, $requirement)
    {
        $item = &$this->jsNeeds[$name];

        $this->setLocalVersion($dep, $name, $item);
        $this->setCdnVersion($dep, $item);

        $this->addDependenciesToListForComponent($dep, $item, self::JS_DEPENDENCIES_KEY, $requirement);
        $this->addDependenciesToListForComponent($dep, $item, self::CSS_DEPENDENCIES_KEY);

        if (!empty($dep['plugins'])) {
            $this->flattenDeps($dep['plugins'], $name);
        }

        if (!empty($dep['clientTemplates'])) {
            $item['clientTemplates'] = $dep['clientTemplates'];
        }
    }

    private function addDependenciesToListForComponent(
        $dependencySourceList,
        &$component,
        $key,
        $requirement = null
    ) {
        if (!empty($dependencySourceList[$key]) || !empty($requirement)) {
            if (empty($component[$key])) {
                $component[$key] = array();
            }

            if (!empty($requirement)) {
                array_push($component[$key], $requirement);
            }

            if (!empty($dependencySourceList[$key])) {
                $component[$key] = array_merge($component[$key], $dependencySourceList[$key]);
            }
        }
    }

    private function lazyInit()
    {
        if (empty($this->jsNeeds)) {
            $this->flattenDeps($this->uiDeps);
        }
    }

    /**
     * @PdInject uiDeps
     * @param $deps
     */
    public function setUiDeps($deps)
    {
        $this->uiDeps = $deps;
    }

    /**
     * @PdInject request
     * @param $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * @return string
     */
    private function determineJqueryUiTheme()
    {
        if (defined('JQUERY_UI_THEME')) {
            return JQUERY_UI_THEME;
        } else {
            return self::DEFAULT_JQUERY_UI_THEME;
        }
    }

    /**
     * @param $requirement
     * @param $dep
     * @param $name
     */
    private function applyLargeScreenEnhancement($requirement, $dep, $name)
    {
        if ($this->largeScreenVersionRequested() && $this->largeScreenEnhancementsDefined($dep)) {
            $this->mapConfigDetails($dep[Request::LARGE_ENHANCEMENT], $name, $requirement);
        }
    }

    /**
     * @param $dep
     * @param $name
     * @param $item
     * @return array
     * @throws MissingLocalPathToResourceException
     */
    private function setLocalVersion($dep, $name, &$item)
    {
        if (!empty($dep[self::LOCAL])) {
            $item[self::LOCAL] = $dep[self::LOCAL];
        } else {
            if (empty($dep[Request::LARGE_ENHANCEMENT])) {
                throw new MissingLocalPathToResourceException($name);
            }
        }
    }

    /**
     * @param $dep
     * @param $item
     */
    private function setCdnVersion(&$dep, &$item)
    {
        if (isset($dep['cdn'])) {
            $item['cdn'] = $dep['cdn'];
        }
    }
}
