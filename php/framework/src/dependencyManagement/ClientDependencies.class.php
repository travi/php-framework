<?php
 
class ClientDependencies
{
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

    private function flattenDeps($deps = array(), $requirement = '')
    {
        if (defined('JQUERY_UI_THEME')) {
            $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = JQUERY_UI_THEME;
        } else {
            $this->jsNeeds['jqueryUiTheme'][self::LOCAL] = '/resources/css/jquery-ui-theme/jquery-ui.css';
        }
        $this->jsNeeds['jcarsouselSkin'][self::LOCAL] = JCAROUSEL_SKIN;

        foreach ($deps as $name => $dep) {
            if (
                $this->request->getEnhancementVersion() === Request::DESKTOP_ENHANCEMENT
                && !empty($dep[Request::DESKTOP_ENHANCEMENT])
            ) {
                $this->mapConfigDetails(
                    $dep[Request::DESKTOP_ENHANCEMENT],
                    $name,
                    $requirement
                );
            } else {
                $this->mapConfigDetails($dep, $name, $requirement);
            }
        }
    }

    private function mapConfigDetails($dep, $name, $requirement)
    {
        $item = array();

        if (!empty($dep[self::LOCAL])) {
            $item[self::LOCAL] = $dep[self::LOCAL];
        } else {
            if (empty($dep[Request::DESKTOP_ENHANCEMENT])) {
                throw new Exception('Local URI required for ' . $name);
            }
        }

        $item['cdn'] = $dep['cdn'];

        if (!empty($dep['jsDependencies'])) {
            $item['jsDependencies'] = $dep['jsDependencies'];
        } else {
            $item['jsDependencies'] = array();
        }
        $item['cssDependencies'] = $dep['cssDependencies'];

        if (!empty($requirement)) {
            array_push($item['jsDependencies'], $requirement);
        }

        if (!empty($dep['plugins'])) {
            $this->flattenDeps($dep['plugins'], $name);
        }

        if (!empty($dep['clientTemplates'])) {
            $item['clientTemplates'] = $dep['clientTemplates'];
        }

        $this->jsNeeds[$name] = $item;
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
