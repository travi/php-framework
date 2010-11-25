<?php
/*
 * Created on Oct 2, 2008
 * By Matt Travi
 * programmer@travi.org
 */

require_once('navigation.class.php');
 
class Accordion extends NavigationObject
{	
	public function __construct($options=array())
	{
		$this->addJavaScript('jqueryUi');
		$this->addJsInit("$('.accordion').accordion({animated: 'easeslide',header: 'dt'});");	
	}
	
	public function __toString()
	{
		$content = '
		<dl class="accordion contentNav">';

		foreach($this->sections as $title => $section)
		{
			$content .= '
			<dt>'.$title.'</dt>
				<dd>
					';

				$sectionContent = $section->getContent();
			$content .= (!empty($sectionContent))?$section:'&nbsp;';

			$content .= '
				</dd>';
		}

		$content .= '
		</dl>';

		return $content;
	}
}
 
?>