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
	
	public function getSection($title)
	{
		return $this->sections[$title];
	}
	
	public function getContentArray()
	{
		$content = array();
		
		foreach($this->sections as $section)
		{
			array_push($content,$section->getContent());
		}
				
		return $content;
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