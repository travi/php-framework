<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
  
class Tabs extends NavigationObject
{	
	function Tabs($options=array())
	{
		$this->addJavaScript('jqueryUi');
		$this->addJsInit("$('.ui-tabs').tabs({selected:0,fx:{opacity:'toggle',height:'toggle'}});");	
	}
	function __toString()
	{
		
		$content = '	
			<div class="ui-tabs">
				<ul>';
		foreach($this->sections as $title => $section)
		{
			$content .= '
					<li><a href="#'.str_replace(' ','_',strtolower($title)).'"><span>'.$title.'</span></a></li>';
		}
		$content .= '
				</ul>';
				
		foreach($this->sections as $title => $section)
		{
			$content .= '
				<div id="'.str_replace(' ','_',strtolower($title)).'">';
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