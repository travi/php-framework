<?php
/*
 * Created on Oct 2, 2008
 * By Matt Travi
 */

 require_once('contentObject.class.php');
 
class Accordion extends ContentObject
{
	var $sections = array();
	
	function Accordion($options=array())
	{
		$this->addJavaScript(JQUERY);
		$this->addJavaScript(JQUERY_UI);
		$this->addJavaScript('/resources/shared/js/jquery/plugins/easing/jquery.easing.js');
		$this->addJsInit('$(".accordion").accordion({animated: \'easeslide\',header: \'dt\'});');	
	}
	
	function addSection($title,$content='')
	{
		$this->sections[$title] = $content;
	}
	
	function addSectionContent($title,$content)
	{
		$this->sections[$title] .= $content;
	}
	
	function addSectionContentLinks($title,$items=array())
	{
		$content = '
						<ul>';
		
		foreach($items as $key => $value)
		{
			if(!is_array($value))
			{
				$content .= '
							<li><a href="'.$value.'">'.$key.'</a></li>';
			}
			else if(!empty($value['link']))
			{
				if($key != "Admin")
				{
					$content .= '
							<li><a href="'.$value['link'].'">'.$key.'</a></li>';
				}
			}
			else
			{
				$content .= '
						<li>'.$key.'
							<ul>';
				
				foreach($value as $text => $link)
				{
					$content .= '
								<li><a href="'.$link.'">'.$text.'</a></li>';
				}
				
				$content .= '
							</ul>
						</li>';
			}
		}
		
		$content .= '
						</ul>';
	
		$this->addSectionContent($title,$content);
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