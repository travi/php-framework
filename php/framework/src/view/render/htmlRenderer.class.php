<?php

class HtmlRenderer extends Renderer
{
    /** @var Smarty */
    private $smarty;
    private $layoutTemplate;
    /** @var DependencyManager */
    private $dependencyManager;
    private $controller;
    private $action;

    /**
     * @param $data
     * @param $page AbstractResponse
     * @return void
     */
    public function format($data, $page)
    {
        $this->dependencyManager->resolveContentDependencies($data);
        $this->dependencyManager->loadPageDependencies($this->controller, $this->action);
        $this->dependencyManager->addCacheBusters();

        $this->smarty->clearAllAssign();
        $this->smarty->assign('dependencies', $this->dependencyManager->getDependencies());
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

    public function setRequestedController($controller)
    {
        $this->controller = $controller;
    }

    public function setRequestedAction($action)
    {
        $this->action = $action;
    }
}
