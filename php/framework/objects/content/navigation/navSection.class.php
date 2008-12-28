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
	}
	
	public function setContent($content)
	{
		$this->sectionContent = $content;		
	}
	
	public function getContent()
	{
		return $this->sectionContent;
	}
	
	public function __toString()
	{				
		if(is_array($this->sectionContent)){
			$content .= '
							<ul class="bulletNav">';
			
			foreach($this->sectionContent as $key => $value)
			{
				if(!is_array($value))
				{
					$content .= '
								<li><a href="'.$value.'">'.$key.'</a></li>';
				}
				else if(!empty($value['link']))
				{
					if($key != "Admin" && $key != "Admin Home")
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
		}else{
			$content .= $this->sectionContent;
			if(is_object($this->sectionContent) && is_a($this->sectionContent,'ContentObject'))
			{
				$this->checkDependencies($this->sectionContent);
			}
		}
						
		return $content;
	}
}

?>