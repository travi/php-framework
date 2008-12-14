<?php
/*
 * Created on Nov 27, 2008
 * By Matt Travi
 */
  
abstract class NavigationObject extends ContentObject
{
	protected $sections = array();
	
	public function addSection($title,$content='')
	{
		$this->sections[$title] = $content;
	}
	
	public function addSectionContent($title,$content)
	{
		$this->sections[$title] .= $content;
	}
	
	public function addSectionContentLinks($title,$items=array())
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