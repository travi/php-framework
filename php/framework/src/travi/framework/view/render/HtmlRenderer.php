<?php

namespace travi\framework\view\render;

use travi\framework\view\render\Renderer,
    travi\framework\dependencyManagement\DependencyManager,
    travi\framework\http\Request,
    travi\framework\utilities\FileSystem,
    travi\framework\page\AbstractResponse,
    travi\framework\exception\MissingPageTemplateException;

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
    public function format($data, $page = null)
    {
        $this->dependencyManager->resolveContentDependencies($data);
        $this->dependencyManager->loadPageDependencies();
        $this->dependencyManager->addCacheBusters();

        $this->setPageTemplateByConvention($page, $data);

        $this->smarty->clearAllAssign();
        $this->smarty->assign('dependencies', $this->dependencyManager->getDependenciesInProperForm());
        $this->smarty->assign('page', $page);
        $this->smarty->assign('showMetaViewport', $this->shouldShowMetaViewport());

        if ($this->request->isAjax()) {
            $this->renderContentSection($data, $page);
        } else {
            $this->renderFullPage();
        }
    }

    /**
     * @param $page AbstractResponse
     * @param $data
     * @throws MissingPageTemplateException
     */
    public function setPageTemplateByConvention(&$page, $data)
    {
        $pageTemplate = $page->getPageTemplate();

        if (empty($pageTemplate)) {
            if (is_array($data) && isset($data['form'])) {
                $pathToTemplate = '../wrap/formWrapper.tpl';
            } else {
                $pathToTemplate = $this->buildTemplatePath();
            }

            if ($this->matchingTemplateExists($pathToTemplate)) {
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

    /**
     * @param $pathToTemplate
     * @return bool
     */
    private function matchingTemplateExists($pathToTemplate)
    {
        return $this->fileSystem->pageTemplateExists($pathToTemplate)
            || $this->fileSystem->frameworkTemplateExists($pathToTemplate);
    }

    /**
     * @param $fileSystem
     * @PdInject fileSystem
     */
    public function setFileSystem($fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    /**
     * @param $data
     * @param $page
     */
    private function renderContentSection($data, $page)
    {
        $this->smarty->assign('content', $data);
        $this->smarty->display('pages/' . $page->getPageTemplate());
    }

    private function renderFullPage()
    {
        $this->smarty->display($this->layoutTemplate);
    }

    /**
     * @return string
     */
    private function buildTemplatePath()
    {
        $controller     = $this->request->getController();
        $action         = $this->request->getAction();
        $pathToTemplate = $controller . '/' . $action . '.tpl';

        if ($this->request->isAdmin()) {
            $pathToTemplate = 'admin/' . $pathToTemplate;
            return $pathToTemplate;
        }
        return $pathToTemplate;
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
}
