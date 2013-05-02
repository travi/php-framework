<?php

namespace Travi\framework\view\render;

use Travi\framework\view\render\Renderer,
    Travi\framework\dependencyManagement\DependencyManager,
    Travi\framework\http\Request,
    Travi\framework\utilities\FileSystem,
    Travi\framework\page\AbstractResponse,
    Travi\framework\exception\MissingPageTemplateException;

class HtmlRenderer extends Renderer
{
    private $layoutTemplate;
    /** @var \Smarty */
    private $smarty;
    /** @var DependencyManager */
    private $dependencyManager;
    /** @var Request */
    private $request;
    /** @var FileSystem */
    private $fileSystem;

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

        $this->setPageTemplateByConvention($page);

        $this->smarty->clearAllAssign();
        $this->smarty->assign('dependencies', $this->dependencyManager->getDependenciesInProperForm());
        $this->smarty->assign('page', $page);
        $this->smarty->assign('showMetaViewport', $this->shouldShowMetaViewport());

        $this->smarty->display($this->layoutTemplate);
    }

    /**
     * @param $page AbstractResponse
     * @throws MissingPageTemplateException
     */
    public function setPageTemplateByConvention(&$page)
    {
        $pageTemplate = $page->getPageTemplate();

        if (empty($pageTemplate)) {
            $controller = $this->request->getController();
            $action = $this->request->getAction();
            $pathToTemplate = $controller . '/' . $action . '.tpl';

            if ($this->request->isAdmin()) {
                $pathToTemplate = 'admin/' . $pathToTemplate;
            }

            if ($this->fileSystem->pageTemplateExists($pathToTemplate)) {
                $page->setPageTemplate($pathToTemplate);
            } else {
                throw new MissingPageTemplateException('No Page Template Available');
            }
        }
    }

    private function shouldShowMetaViewport()
    {
        $enhancementVersion = $this->request->getEnhancementVersion();
        return ($enhancementVersion === Request::SMALL_ENHANCEMENT
                || $enhancementVersion === Request::BASE_ENHANCEMENT);
    }

    public function setLayoutTemplate($layoutTemplate)
    {
        $this->layoutTemplate = $layoutTemplate;
    }

    /**
     * @PdInject Smarty
     * @param $smarty \Smarty
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

    /**
     * @param $fileSystem
     * @PdInject fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }
}
