<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
  
class Tabs extends NavigationObject
{	
	function Tabs($options=array())
	{
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_UI);
		$this->addJsInit("$('.tabs > ul').tabs({selected:0,fx:{opacity:'toggle',height:'toggle'}});");	
	}
	function __toString()
	{
		
		$content = '	
			<div class="flora tabs">
				<ul class="ui-tabs-nav">';
		foreach($this->sections as $title => $section)
		{
			$content .= '
					<li class="ui-tabs-nav-item"><a href="#'.str_replace(' ','_',strtolower($title)).'"><span>'.$title.'</span></a></li>';
		}
		$content .= '
				</ul>';
				
		foreach($this->sections as $title => $section)
		{
			$content .= '
				<div id="'.str_replace(' ','_',strtolower($title)).'" class="ui-tabs-panel">';
				$sectionContent = $section->getContent();
			$content .= (!empty($sectionContent))?$section:'&nbsp;';
			$content .= '
				</div>';
			$this->checkDependencies($section);
		}
		$content .= '
			</div>';
				
		return $content;
	}
}
 
?>