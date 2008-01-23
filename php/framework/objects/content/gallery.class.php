<?php

class Gallery extends ContentObject
{
    function Gallery()
    {
		$this->setDependencies();
    }
    function setDependencies()
    {
		$this->addStyleSheet('/reusable/css/gallery/gallery.dev.css');

		$this->addJavaScript('/reusable/js/jQuery/jquery.js');
		$this->addJavaScript('/reusable/js/jQuery/plugins/jquery.dimensions.min.js');
		$this->reflectionDependencies();
		$this->lightBoxDependencies();
		$this->carouselDependencies();

		$this->addJavaScript('/reusable/js/gallery/gallery.js');
    }
    function reflectionDependencies()
    {
		 $this->addJavaScript('/reusable/js/reflection/reflection.js');
    }
    function lightBoxDependencies()
    {
    	$this->addStyleSheet('/reusable/js/lightbox/css/lightbox.css');
		$this->addJavaScript('/reusable/js/lightbox/js/prototype.js');
		$this->addJavaScript('/reusable/js/lightbox/js/scriptaculous.js?load=effects');
		$this->addJavaScript('/reusable/js/lightbox/js/lightbox.js');
    }
    function carouselDependencies()
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
	function menu()
	{
		return '
			<div id="msc_menu"></div>';
	}
    function carousel()
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
    function toString()
    {
    	return $this->menu().$this->carousel().'

			<div id="msc_image">

				<div id="image_div">
					<div id="image_container">
						<div id="image_nav">
							<div class="button left"><a href="javascript:;" onclick="prevImage()">&#171; previous image</a></div>
							<div class="button right"><a href="javascript:;" onclick="nextImage()">next image &#187;</a></div>
						</div>
						<!-- <div id="img"> -->
							<img id="preview_pos" class="imagen preview reflect" src="galleries/camaro/image1.jpg" title="image1" alt="image1" />
							<!-- onload="Reflection.add(this);" -->
						<!-- </div> -->
						<div id="image_title"></div>
					</div>
				</div>
				<a href="galleries/camaro/image1.jpg" rel="lightbox" onclick="myLightbox.start(this); return false;">
					<img src="galleries/camaro/image1.jpg" title="image1" alt="image1" class="preview" id="preview_overlay"/>
				</a>

				<!-- <div id="overlay_img">
					<div id="space_tool">
						<a href="galleries/camaro/image1.jpg" rel="lightbox" onclick="myLightbox.start(this); return false;">
							<img src="galleries/camaro/image1.jpg" title="image1" alt="image1" class="preview" id="preview_overlay"/>
						</a>
					</div>
				</div> -->

				<div id="gallery_info">
					click on a gallery from the menu above
				</div>
			</div>';
    }
}
?>