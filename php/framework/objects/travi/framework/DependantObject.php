<?php

namespace travi\framework;

abstract class DependantObject
{
    private $styles = array();
    private $scripts = array();
    private $jsInits = array();
    protected $template;

    public function getStyles()
    {
        return $this->styles;
    }
    public function getJavaScripts()
    {
        return $this->scripts;
    }
    public function getJsInits()
    {
        return $this->jsInits;
    }

    public function addStyleSheet($sheet)
    {
        array_push($this->styles, $sheet);
    }
    public function addJavaScript($script)
    {
        array_push($this->scripts, $script);
    }
    public function addJsInit($init)
    {
        array_push($this->jsInits, $init);
    }
    public function setTemplate($template)
    {
        $this->template = $template;
    }
    public function getTemplate()
    {
        return $this->template;
    }

    public function getDependencies()
    {
        return array(
            'scripts'   => $this->getJavaScripts(),
            'jsInits' => $this->getJsInits(),
            'styles' => $this->getStyles()
        );
    }

    protected function addDependencies($dependencies = array())
    {
        $scripts = $dependencies['scripts'];
        $jsInits = $dependencies['jsInits'];
        $styles = $dependencies['styles'];

        if (isset($scripts)) {
            foreach ($scripts as $jsDependency) {
                $this->addJavaScript($jsDependency);
            }
        }
        if (isset($jsInits)) {
            foreach ($jsInits as $jsInit) {
                $this->addJsInit($jsInit);
            }
        }
        if ($styles) {
            foreach ($styles as $styleDependency) {
                $this->addStyleSheet($styleDependency);
            }
        }
    }
}