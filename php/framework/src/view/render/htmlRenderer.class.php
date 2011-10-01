<?php

class HtmlRenderer extends Renderer
{
    private $layoutTemplate;
    /** @var Smarty */
    private $smarty;
    /** @var DependencyManager */
    private $dependencyManager;
    /** @var Request */
    private $request;

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
        $this->smarty->assign(
            'isMobile',
            ($this->request->getEnhancementVersion() === Request::MOBILE_ENHANCEMENT)
        );
        $this->smarty->display($this->layoutTemplate);
    }

    public function setLayoutTemplate($layoutTemplate)
    {
        $this->layoutTemplate = $layoutTemplate;
    }

    /**
     * @PdInject Smarty
     * @param $smarty Smarty
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
