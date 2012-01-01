<?php
 
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

        return $this->jsNeeds[$item];
    }

    public function resolveFileURI($resource)
    {
        $this->lazyInit();

        return $this->jsNeeds[$resource][self::LOCAL];
    }

    private function flattenDeps($deps = array())
    {
        if (defined('JQUERY_UI_THEME')) {
            $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = JQUERY_UI_THEME;
        } else {
            $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = self::DEFAULT_JQUERY_UI_THEME;
        }

        foreach ($deps as $name => $dep) {
            $this->mapConfigDetails($dep, $name);
            if ($this->desktopVersionRequested() && $this->desktopEnhancementsDefined($dep)) {
                $this->mapConfigDetails($dep[Request::DESKTOP_ENHANCEMENT], $name);
            }
        }
    }

    private function desktopEnhancementsDefined($dep)
    {
        return !empty($dep[Request::DESKTOP_ENHANCEMENT]);
    }

    private function desktopVersionRequested()
    {
        return $this->request->getEnhancementVersion() === Request::DESKTOP_ENHANCEMENT;
    }

    private function mapConfigDetails($dep, $name)
    {
        $item = &$this->jsNeeds[$name];

        if (!empty($dep[self::LOCAL])) {
            $item[self::LOCAL] = $dep[self::LOCAL];
        } else {
            if (empty($dep[Request::DESKTOP_ENHANCEMENT])) {
                throw new Exception('Local URI required for ' . $name);
            }
        }

        $item['cdn'] = $dep['cdn'];

        $this->addDependenciesToListForComponent($dep, $item, self::JS_DEPENDENCIES_KEY);
        $this->addDependenciesToListForComponent($dep, $item, self::CSS_DEPENDENCIES_KEY);

        if (!empty($dep['plugins'])) {
            $this->flattenDeps($dep['plugins'], $name);
        }

        if (!empty($dep['clientTemplates'])) {
            $item['clientTemplates'] = $dep['clientTemplates'];
        }
    }

    private function addDependenciesToListForComponent($dependencySourceList, &$component, $key)
    {

        if (!empty($dependencySourceList[$key])) {
            if (empty($component[$key])) {
                $component[$key] = array();
            }
            $component[$key] = array_merge($dependencySourceList[$key], $component[$key]);
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
