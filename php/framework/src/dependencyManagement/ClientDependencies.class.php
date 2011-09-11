<?php
 
class ClientDependencies
{
    private $jsNeeds = array();

    public function __construct()
    {
        global $uiDeps;

        $this->flattenDeps($uiDeps);

        if (defined('JQUERY_UI_THEME')) {
            $this->jsNeeds['jqueryUiTheme']['local'] = JQUERY_UI_THEME;
        } else {
            $this->jsNeeds['jqueryUiTheme']['local'] = '/resources/css/jquery-ui-theme/jquery-ui.css';
        }
        $this->jsNeeds['jcarsouselSkin']['local'] = JCAROUSEL_SKIN;
    }

    private function flattenDeps($deps = array(), $requirement = '')
    {
        foreach ($deps as $name => $dep) {
            $item = array();

            if (!empty($dep['local'])) {
                $item['local'] = $dep['local'];
            } else {
                throw new Exception('Local URI required for ' . $name);
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
    }

    public function getDependenciesFor($item)
    {
        return $this->jsNeeds[$item];
    }

    public function resolveFileURI($resource)
    {
        return $this->jsNeeds[$resource]['local'];
    }
}
