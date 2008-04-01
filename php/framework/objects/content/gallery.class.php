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

		$this->addJavaScript('/reusable/js/jQuery/jquery.js');
		$this->reflectionDependencies();
		$this->lightboxDependencies();
		$this->jcarouselDependencies();
		$this->addJavaScript('/reusable/js/jQuery/plugins/dimensions/jquery.dimensions.js');

		$this->addJavaScript('/reusable/js/gallery/gallery.js');
    }
    function reflectionDependencies()
    {
		 $this->addJavaScript('/reusable/js/reflection/reflection.js');
    }
    function lightBoxDependencies()
    {
    	$this->addStyleSheet('/reusable/js/jQuery/plugins/lightbox/css/jquery.lightbox-0.4.css');
		$this->addJavaScript('/reusable/js/jQuery/plugins/lightbox/jquery.lightbox-0.4.js');
    }
    function thickboxDependencies()
    {
    	$this->addStyleSheet('/reusable/js/jQuery/plugins/thickbox/thickbox.css');
		$this->addJavaScript('/reusable/js/jQuery/plugins/thickbox/thickbox.js');

    }
    function ycarouselDependencies()
    {
		 $this->addJavaScript('/reusable/js/yahoo/yui/build/yahoo/yahoo.js');
		 $this->addJavaScript('/reusable/js/yahoo/yui/build/event/event.js');
		 $this->addJavaScript('/reusable/js/yahoo/yui/build/container/container_core.js');
		 $this->addJavaScript('/reusable/js/yahoo/yui/build/connection/connection.js');
		 $this->addJavaScript('/reusable/js/yahoo/yui/build/dom/dom.js');
		 $this->addJavaScript('/reusable/js/yahoo/yui/build/animation/animation.js');
		 $this->addJavaScript('/reusable/js/carousel/carousel.js');
		 $this->addJavaScript('/reusable/js/carousel/load.js');
		// $this->addJavaScript('/reusable/js/carousel/carousel_load.js');
		 $this->addStyleSheet('/reusable/js/carousel/carousel.css');
		 $this->addStyleSheet('/reusable/css/gallery/carousel_overrides.css');
    }
    function jcarouselDependencies()
    {
		$this->addJavaScript("/reusable/js/jQuery/plugins/jcarousel/jquery.jcarousel.js");
		$this->addStyleSheet("/reusable/js/jQuery/plugins/jcarousel/jquery.jcarousel.css");
		$this->addStyleSheet("/reusable/js/jQuery/plugins/jcarousel/skins/tango/skin.css");

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
    function ycarousel()
    {
    	return '
			<div id="thumbs-carousel" class="carousel-component">
				<div class="carousel-prev">
					<img id="prev-arrow" class="left-button-image" src="/reusable/images/left-enabled.gif" alt="left"/>
				</div>
				<div class="carousel-next">
					<img id="next-arrow" class="right-button-image" src="/reusable/images/right-enabled.gif" alt="right"/>
				</div>
				<div class="carousel-clip-region">
					<ul class="carousel-list">
					</ul>
			    </div>
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
    function toString()
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