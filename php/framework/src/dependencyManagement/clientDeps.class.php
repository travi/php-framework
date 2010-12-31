<?php
/**
 * Created by PhpStorm.
 * User: travi
 * Date: Dec 31, 2010
 * Time: 12:00:20 PM
 * To change this template use File | Settings | File Templates.
 */
 
class clientDeps
{
    private $jsNeeds = array();

	public function __construct()
	{
    	global $uiDeps;

        $this->flattenDeps($uiDeps);
	}

    private function flattenDeps($deps, $requirement = '')
    {
        foreach($deps as $name => $dep)
        {
            $item = array();

            if(!empty($dep['local']))
            {
                $item['local'] = $dep['local'];
            }
            else
            {
                throw new Exception('Local URI required for '.$name);
            }

            $item['cdn'] = $dep['cdn'];

            if(!empty($dep['jsDependencies']))
            {
                $item['jsDependencies'] = $dep['jsDependencies'];
            }
            else
            {
                $item['jsDependencies'] = array();
            }
            $item['cssDependencies'] = $dep['cssDependencies'];

            if(!empty($requirement))
            {
                array_push($item['jsDependencies'], $requirement);
            }

            if(!empty($dep['plugins']))
            {
                $this->flattenDeps($dep['plugins'], $name);
            }

            $this->jsNeeds[$name] = $item;
        }
    }

    public function getDependenciesFor($item)
    {
        return $this->jsNeeds[$item];
    }
}
