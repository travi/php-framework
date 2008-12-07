<?php
/*
 * Created on Oct 2, 2008
 * By Matt Travi
 */

require_once('navigation.class.php');
 
class Accordion extends NavigationObject
{
	var $sections = array();
	
	function Accordion($options=array())
	{
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_UI);
		$this->addJavaScript('/resources/shared/js/jquery/plugins/easing/jquery.easing.js');
		$this->addJsInit("$('.accordion').accordion({animated: 'easeslide',header: 'dt'});");	
	}
	
	function toString()
	{
		$content = '			
		<dl class="entry accordion contentNav">';
		
		foreach($this->sections as $title => $body)
		{
			$content .= '			
			<dt class="entry-title">'.$title.'</dt>
				<dd>
					';
					
			$content .= (!empty($body))?$body:'&nbsp;';
			
			$content .= '
				</dd>';
		}
		
		$content .= '
		</dl>';
		
		return $content;
	}
}
 
?>