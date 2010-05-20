<?php

class Gallery extends ContentObject
{	
	var $thumbs = array();
	var $albums = array();
	var $thumbs_theme;

    function Gallery($albums)
    {
    	$this->albums = $albums['albums'];
		$this->addJavaScript('gallery');
    }
    function setThumbs($thumbs=array())
    {
    	$this->thumbs = $thumbs;
    }
    function setThumbsTheme($theme)
    {
    	$this->thumbs_theme = $theme;
    }
	function menu($albums=array())
	{
		$content = '
			<ul id="albumMenu">';
				
		foreach($albums as $album)
		{
			$content .= '
					<li>&nbsp;|&nbsp;<a href="/gallery/?album='.$album['name'].'">'.$album['name'].'</a></li>';
		}
						
		$content .= '
			</ul>';
			
		return $content;
	}
    function jcarousel()
    {
		$carousel = '
			<div id="thumbContainer" class="entry-message">
				<ul class="carousel jcarousel-skin-travi" id="thumb-carousel">';

//		foreach($this->thumbs as $thumb)
//		{
//			$carousel .= '
//					<li><img src="/gallery/images/thumbs/'.$thumb["filename"].'" /></li>';
//		}

		$carousel .= '
				</ul>
			</div>';

		return $carousel;
    }
    function __toString()
    {
    	return $this->menu($this->albums).'<div class="section">'.$this->jcarousel().'</div>';
    }
}
?>