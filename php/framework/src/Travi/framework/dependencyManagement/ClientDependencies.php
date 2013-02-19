<?php

namespace Travi\framework\dependencyManagement;

use Travi\framework\http\Request,
    Travi\framework\exception\MissingLocalPathToResourceException;

class ClientDependencies
{
    const DEFAULT_JQUERY_UI_THEME = '/resources/css/jquery-ui-theme/jquery-ui.css';
    const CSS_DEPENDENCIES_KEY = 'cssDependencies';
    const JS_DEPENDENCIES_KEY = 'jsDependencies';
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
        }
    }

    private function flattenDeps($deps = array(), $requirement = null)
    {
        if (defined('JQUERY_UI_THEME')) {
            $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = JQUERY_UI_THEME;
        } else {
            $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = self::DEFAULT_JQUERY_UI_THEME;
        }

        foreach ($deps as $name => $dep) {
            $this->mapConfigDetails($dep, $name, $requirement);
            if ($this->desktopVersionRequested() && $this->desktopEnhancementsDefined($dep)) {
                $this->mapConfigDetails($dep[Request::LARGE_ENHANCEMENT], $name, $requirement);
            }
        }
    }

    private function desktopEnhancementsDefined($dep)
    {
        return !empty($dep[Request::LARGE_ENHANCEMENT]);
    }

    private function desktopVersionRequested()
    {
        return $this->request->getEnhancementVersion() === Request::LARGE_ENHANCEMENT;
    }

    private function mapConfigDetails($dep, $name, $requirement)
    {
        $item = &$this->jsNeeds[$name];

        if (!empty($dep[self::LOCAL])) {
            $item[self::LOCAL] = $dep[self::LOCAL];
        } else {
            if (empty($dep[Request::LARGE_ENHANCEMENT])) {
                throw new MissingLocalPathToResourceException($name);
            }
        }

        if (isset($dep['cdn'])) {
            $item['cdn'] = $dep['cdn'];
        }

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
}
