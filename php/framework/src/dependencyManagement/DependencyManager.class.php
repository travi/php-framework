<?php
/**
 * Created by PhpStorm.
 * User: travi
 * Date: Dec 31, 2010
 * Time: 1:54:32 PM
 * To change this template use File | Settings | File Templates.
 */
 
class DependencyManager
{
    private $clientDependencyDefinitions;
    private $requirementLists = array();

	public function __construct()
	{
        $this->clientDependencyDefinitions = new ClientDependencies();
	}

    public function addDependency($dep, $category, $index)
    {
        if($category === 'js' || $category === 'JavaScript' || $category === 'javascript')
        {
            $this->addJavaScript($dep);
        }
        else if($category === 'css' || $category === 'StyleSheet' || $category === 'stylesheet')
        {
            $this->addStyleSheet($dep, $index);
        }
        else if($category === 'jsInit' || $category === 'jsinit')
        {
            $this->addJsInit($dep);
        }
    }

    public function addJavaScript($script)
    {
        $this->lazyInitializeList('js');

        $dependencies = $this->clientDependencyDefinitions->getDependenciesFor($script);

		if(!empty($dependencies))
		{
			if(!empty($dependencies['jsDependencies']))
			{
				foreach($dependencies['jsDependencies'] as $dependency)
				{
					$this->addJavaScript($dependency);
				}
			}
			if(!empty($dependencies['cssDependencies']))
			{
				foreach($dependencies['cssDependencies'] as $dependency)
				{
                    $this->addStyleSheet($dependency);
				}
			}
            $script = $this->clientDependencyDefinitions->resolveFileURI($script);
		}
		if(!in_array($script,$this->requirementLists['js']))
		{
            array_push($this->requirementLists['js'], $script);
		}

    }

	public function addStyleSheet($sheet, $index="")
	{
        $this->lazyInitializeList('css');

        $resolved = $this->clientDependencyDefinitions->resolveFileURI($sheet);

        if(!empty($resolved))
        {
            $sheet = $resolved;
        }

		if(!in_array($sheet, $this->requirementLists['css']))
		{
			if(!empty($index))
			{
				$this->requirementLists['css'][$index] = $sheet;
			}
			else
            {
                array_push($this->requirementLists['css'], $sheet);
            }
		}
	}

	public function addJsInit($init)
	{
        $this->lazyInitializeList('jsInit');

        array_push($this->requirementLists['jsInit'], $init);
	}

    public function getDependencies($category)
    {
        if($category === 'js' || $category === 'JavaScript' || $category === 'javascript')
        {
            return $this->getScripts();
        }
        else if($category === 'css' || $category === 'StyleSheet' || $category === 'stylesheet')
        {
            return $this->getStyleSheets();
        }
        else if($category === 'jsInit' || $category === 'jsinit')
        {
            return $this->getJsInits();
        }
    }

    public function getScripts()
    {
        return $this->requirementLists['js'];
    }

	public function getStyleSheets()
	{
        uksort($this->requirementLists['css'], 'strnatcasecmp');

		return $this->requirementLists['css'];
	}

	public function getJsInits()
	{
		return $this->requirementLists['jsInit'];
	}

    public function addDependencies($dependencies = array())
    {
        if(!empty($dependencies['scripts']))
        {
            foreach($dependencies['scripts'] as $script)
            {
                $this->addJavaScript($script);
            }
        }
        if(!empty($dependencies['jsInits']))
        {
            foreach($dependencies['jsInits'] as $init)
            {
                $this->addJsInit($init);
            }
        }
        if(!empty($dependencies['styles']))
        {
            foreach($dependencies['styles'] as $style)
            {
                $this->addStyleSheet($style);
            }
        }
    }

    public function resolveComponentDependencies($component)
    {
        if(is_object($component) && is_a($component,'DependantObject'))
        {
            $this->addDependencies($component->getDependencies());
        }
        else if(is_array($component))//TODO: need to make this DRY
        {
            foreach($component as $innerComponent)
            {
                $this->resolveComponentDependencies($innerComponent);
            }
        }
    }

	public function resolveContentDependencies($content)
	{
        if(is_array($content))
        {
            foreach($content as $component)
            {
                $this->resolveComponentDependencies($component);
            }
        }
        else
        {
            $this->resolveComponentDependencies($content);
        }
	}

    private function lazyInitializeList($category)
    {
        if(!isset($this->requirementLists[$category]))
        {
            $this->requirementLists[$category] = array();
        }
    }
}
