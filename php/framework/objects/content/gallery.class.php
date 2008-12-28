<?php

class Gallery extends ContentObject
{
	var $thumbs = array();
	var $thumbs_theme;

    function Gallery()
    {
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
		$this->addStyleSheet("/resources/shared/js/jquery/plugins/jcarousel/skins/tango/skin.css");

    }
	function menu()
	{
		return '
			<div id="msc_menu">
			<!--
				<ul>
						<li>&nbsp;|&nbsp;<a href="#">camaro</a></li>
						<li>&nbsp;|&nbsp;<a href="#">corvette</a></li>
						<li>&nbsp;|&nbsp;<a href="#">truck</a></li>
				</ul>
			-->
			</div>';
	}
    function jcarousel()
    {
		$carousel = '
				<ul class="carousel jcarousel-skin-tango">';

//		foreach($this->thumbs as $thumb)
//		{
//			$carousel .= '
//					<li><img src="/gallery/images/thumbs/'.$thumb["filename"].'" /></li>';
//		}

		$carousel .= '
				</ul>';

		return $carousel;
    }
    function __toString()
    {
    	return $this->menu().$this->jcarousel().'

		<!--	<div id="msc_image">

				<div id="image_div">
					<div id="image_container"> -->
					<!--
						<div id="image_nav">
							<div class="button left"><a href="javascript:;" onclick="prevImage()">&#171; previous image</a></div>
							<div class="button right"><a href="javascript:;" onclick="nextImage()">next image &#187;</a></div>
						</div>
					-->
				<!--		<img id="preview_pos" class="imagen preview reflect" src="images/camaro_1.jpg" title="image1" alt="image1" />
						<div id="image_title"></div>
					</div>
				</div>
				<img src="images/camaro_1.jpg" alt="preview" class="preview" id="preview_overlay"/>
				<div id="gallery_info">
					click on a gallery from the menu above
				</div>
			</div> -->';
    }
}
?>