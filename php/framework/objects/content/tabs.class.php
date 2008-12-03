<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
  
class Tabs extends NavigationObject
{
	var $sections = array();
	
	function Tabs($options=array())
	{
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_UI);
		$this->addJsInit("$('.tabs > ul').tabs({selected:0,fx:{opacity:'toggle',height:'toggle'}});");	
	}
	function toString()
	{
		$content = '	
			<div class="flora tabs">
				<ul class="ui-tabs-nav">';
		foreach($this->sections as $title => $body)
		{
			$content .= '
					<li class="ui-tabs-nav-item"><a href="#'.strtolower($title).'"><span>'.$title.'</span></a></li>';
		}
		$content .= '
				</ul>';
				
		foreach($this->sections as $title => $body)
		{
			$content .= '
				<div id="'.strtolower($title).'" class="ui-tabs-panel">';
			$content .= $body;
			$content .= '
				</div>';
		}
				
		return $content;
	}
}
 
?>