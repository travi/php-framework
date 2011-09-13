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

        $smarty = $this->getSmarty();

        $smarty->clearAllAssign();
        $smarty->assign('page', $page);
        $smarty->display($this->layoutTemplate);
    }

    private function getSmarty()
    {
        if (empty($this->smarty)) {
            $this->smartyInit();
        }
        return $this->smarty;
    }

    private function smartyInit()
    {
        global $config;

        if (!isset($this->smartyConfig)) {
            $this->getSmartyConfig();
        }

        include_once $this->smartyConfig['pathToSmarty'];

        $smarty = new Smarty();

        $smarty->template_dir = array(
            $this->smartyConfig['siteTemplateDir'],
            $this->smartyConfig['sharedTemplateDir']
        );
        $smarty->compile_dir = $this->smartyConfig['smartyCompileDir'];
        $smarty->cache_dir = $this->smartyConfig['smartyCacheDir'];
        $smarty->config_dir = $this->smartyConfig['smartyConfigDir'];

        if ($config['debug']) {
            $smarty->force_compile = true;
        } else {
            $smarty->compile_check = false;
        }

        $this->smarty = $smarty;
    }

    private function getSmartyConfig()
    {
        $this->smartyConfig = Spyc::YAMLLoad(SMARTY_CONFIG);
    }

    public function setLayoutTemplate($layoutTemplate)
    {
        $this->layoutTemplate = $layoutTemplate;
    }
}
