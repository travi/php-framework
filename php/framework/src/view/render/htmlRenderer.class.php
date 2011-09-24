<?php

class HtmlRenderer extends Renderer
{
    /** @var Smarty */
    private $smarty;
    private $layoutTemplate;
    /** @var DependencyManager */
    private $dependencyManager;

    /**
     * @param $data
     * @param $page AbstractResponse
     * @return void
     */
    public function format($data, $page)
    {
        $this->dependencyManager->resolveContentDependencies($data);
        $this->dependencyManager->loadPageDependencies();
        $this->dependencyManager->addCacheBusters();

        $this->smarty->clearAllAssign();
        $dependencies = $this->dependencyManager->getDependenciesInProperForm();
        $this->smarty->assign('dependencies', $dependencies);
        $this->smarty->assign('page', $page);
        $this->smarty->display($this->layoutTemplate);
    }

    public function setLayoutTemplate($layoutTemplate)
    {
        $this->layoutTemplate = $layoutTemplate;
    }

    /**
     * @PdInject Smarty
     */
    public function setSmarty($smarty)
    {
        $this->smarty = $smarty;
    }

    /**
     * @PdInject dependencyManager
     * @param $dependencyManager DependencyManager
     * @return void
     */
    public function setDependencyManager($dependencyManager)
    {
        $this->dependencyManager = $dependencyManager;
    }
}
