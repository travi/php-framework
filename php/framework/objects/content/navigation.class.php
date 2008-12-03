<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
  
require_once('contentObject.class.php');
  
class NavigationObject extends ContentObject
{
	var $sections = array();

	function NavigationObject()
	{
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
}
?>