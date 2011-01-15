<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
require_once(dirname(__FILE__).'/../../dependantObject.class.php');
require_once(dirname(__FILE__).'/../contentObject.class.php');
  
class NavigationObject extends ContentObject
{
	private $sections = array();
	
	public function addSection($title,$content='')
	{
		$this->setSection($title, $content);
	}

    public function setSection($title,$content='')
    {
        $this->sections[$title] = $content;
    }
	
	public function getSection($title)
	{
		return $this->sections[$title];
	}

    public function getSections()
    {
        return $this->sections;
    }
	
	public function addSectionContent($title,$content)
	{
		$this->setSection($title, $content);
	}
	
	public function addSectionContentLinks($title,$items=array())
	{
		$this->setSection($title, $items);
	}
}
?>