<?php

class HtmlRenderer extends Renderer
{
    /** @var Smarty */
    private $smarty;
    private $layoutTemplate;

    /**
     * @param $data
     * @param $page AbstractResponse
     * @return void
     */
    public function format($data, $page)
    {
        $dependencyManager = $page->getDependencyManager();
        if (isset($dependencyManager)) {
            $dependencyManager->resolveContentDependencies($data);
            $dependencyManager->addCacheBusters();
        }

        $this->smarty->clearAllAssign();
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
}
