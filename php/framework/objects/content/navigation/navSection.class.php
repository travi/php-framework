<?php
/*
 * Created on Dec 14, 2008
 * By Matt Travi
 * programmer@travi.org
 */
class NavSection extends ContentObject
{
	private $sectionTitle;
	private $sectionContent;
	
	public function __construct($title,$content='')
	{
		$this->sectionTitle = $title;
		$this->setContent($content);
        $this->setTemplate('components/navSection.tpl');
	}
	
	public function setContent($content)
	{
		$this->sectionContent = $content;		
	}
	
	public function getContent()
	{
		return $this->sectionContent;
	}
}

?>