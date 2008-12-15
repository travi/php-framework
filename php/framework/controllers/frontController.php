<?php
/*
 * Created on Dec 14, 2008
 * By Matt Travi
 * programmer@travi.org
 */

$controller = new FrontController;

$controller->Display();

class FrontController
{
	private $application;
	private $page;
	private $id;
		
	public function processRequest()
	{
		
	}
	
	private function getApplications()
	{
		
	}
	
	private function getPages($application)
	{
		
	}
 
	public function Display()
	{
		if($_SERVER['X-Requested-With'] == 'XMLHttpRequest')
		{
			echo $this->content;
		}
		else
		{
			if(!isset($this->smartyConfig))
				$this->getSmartyConfig();
	
	        require_once($this->smartyConfig['pathToSmarty']);
				
			uksort($this->stylesheets, 'strnatcasecmp');
	
			$smarty = new Smarty();
	
			$smarty->template_dir = $this->smartyConfig['smartyTemplateDir'];
			$smarty->compile_dir = $this->smartyConfig['smartyCompileDir'];
			$smarty->cache_dir = $this->smartyConfig['smartyCacheDir'];
			$smarty->config_dir = $this->smartyConfig['smartyConfigDir'];
	
			if($this->debug)
			{
				$smarty->force_compile = true;
			}
			else
			{
				$smarty->compile_check = false;
			}
	
			$smarty->assign('page',$this);
			$smarty->display($this->smartyTemplate);
		}
	}
}
?>