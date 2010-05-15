<?php

class Gallery extends ContentObject
{
	var $thumbs = array();
	var $albums = array();
	var $thumbs_theme;

    function Gallery($albums)
    {
    	$this->albums = $albums['albums'];
		$this->setDependencies();
    }
    function setThumbs($thumbs=array())
    {
    	$this->thumbs = $thumbs;
    }
    function setThumbsTheme($theme)
    {
    	$this->thumbs_theme = $theme;
    }
    function setDependencies()
    {
		$this->addStyleSheet('/reusable/css/gallery/gallery.dev.css');

		$this->addJavaScript(JQUERY);
		$this->reflectionDependencies();
		$this->lightboxDependencies();
		$this->jcarouselDependencies();

		$this->addJavaScript('/resources/js/gallery.js');
    }
    function reflectionDependencies()
    {
		 $this->addJavaScript(REFLECTION_JS);
    }
    function lightBoxDependencies()
    {
    	$this->addStyleSheet('/resources/shared/js/jquery/plugins/lightbox/css/jquery.lightbox.css');
		$this->addJavaScript(JQUERY_LIGHTBOX);
    }
    function jcarouselDependencies()
    {
		$this->addJavaScript(JCAROUSEL);
		$this->addStyleSheet("/resources/shared/js/jquery/plugins/jcarousel/jquery.jcarousel.css");
		$this->addStyleSheet("/resources/css/widgets/jcarousel-skin.css");

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