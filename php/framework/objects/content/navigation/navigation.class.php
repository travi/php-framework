<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
  
class NavigationObject extends ContentObject
{
	protected $sections = array();
	
	public function addSection($title,$content='')
	{
		$this->sections[$title] = new NavSection($title,$content);
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
		$this->sections[$title]->setContent($content);
	}
	
	public function addSectionContentLinks($title,$items=array())
	{
		$this->sections[$title]->setContent($items);
	}
}
?>