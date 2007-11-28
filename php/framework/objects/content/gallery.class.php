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

		$this->reflectionDependencies();
		$this->lightBoxDependencies();
		$this->carouselDependencies();
    }
    function reflectionDependencies()
    {
		 $this->addJavaScript('/reusable/js/reflection/jquery/jquery.js');
		 $this->addJavaScript('/reusable/js/reflection/jquery/jquery.offset.js');
		 $this->addJavaScript('/reusable/js/reflection/jquery/interface/iutil.js');
		 $this->addJavaScript('/reusable/js/reflection/jquery/interface/ifx.js');
		 $this->addJavaScript('/reusable/js/reflection/jquery/interface/ifxslide.js');
		 $this->addJavaScript('/reusable/js/reflection/jquery/interface/ifxblind.js');
		 $this->addJavaScript('/reusable/js/reflection/jquery.gallery.js');
		 $this->addJavaScript('/reusable/js/reflection/reflection/reflection.js');
		 $this->addJavaScript('/reusable/js/reflection/images.js');
		 $this->addJavaScript('/reusable/js/reflection/menu.js');
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
    function toString()
    {
    	return '<!-- menu div -->
			<div id="msc_menu"></div>
			<!-- menu div -->

			<!-- Carousel Structure -->
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
			</div>
			<!-- End Carousel Structure -->

			<div id="msc_image">

				<div id="image_div">
					<div id="image_container">
						<div id="image_nav">
							<div class="button left"><a href="javascript:;" onclick="prevImage()">&#171; previous image</a></div>
							<div class="button right"><a href="javascript:;" onclick="nextImage()">next image &#187;</a></div>
						</div>
						<div id="img">
							<img id="mainimg" class="imagen" src="galleries/camaro/image1.jpg" title="image1" alt="image1" height="450" width="600" onload="Reflection.add(this);" />
						</div>
						<div id="image_title"></div>
					</div>
				</div>

				<div id="overlay_img">
					<div id="space_tool">
						<a href="galleries/camaro/image1.jpg" rel="lightbox" onclick="myLightbox.start(this); return false;">
							<img src="galleries/camaro/image1.jpg" title="image1" alt="image1" height="450" width="600" />
						</a>
					</div>
				</div>

				<div id="gallery_info">
					click on a gallery from the menu above
				</div>
			</div>

			<div class="sectionHeader">
				Credits
			</div>
			<div class="text-block">'.$this->galleryCredits().'
			</div>

			<div class="sectionHeader">
				Gallery ToDo List
			</div>
			<div class="text-block">'.$this->galleryToDo().'
			</div>';
    }
	function galleryCredits()
	{
	 	return '

				<ul>
					<li><a href="http://billwscott.com/carousel/">Carousel Component</a> by Bill Scott</li>
					<li><a href="http://www.findmotive.com/2006/08/29/create-square-image-thumbnails-with-php/">Square Thumbnail Script</a> by Noah Winecoff</li>
					<li><a href="http://www.huddletogether.com/projects/lightbox2/">Lightbox.js v2.0</a> by Lokesh Dhakar</li>
					<li><a href="http://cow.neondragon.net/stuff/reflection/">Reflection.js</a> by Cow</li>
					<li><a href="http://mike.teczno.com/json.html">JSON-PHP</a> by Michal Migurski</li>
				</ul>';
	}
	function galleryToDo()
	{
	 	return '
				<ul>
					<li><strike>Add lightbox functionality for viewing the full size image</strike> 10/24/2006</li>
					<li><strike>Build upload tool that creates thumbnails and preview sizes of images</strike> 11/3/2006</li>
					<li><strike>Get carousel animation working</strike> 11/6/2006</li>
					<li><strike>Implement reflection widget</strike> 12/8/2006</li>
					<li><strike>Look into stopping the inheritance of opacity so images can be fully opaque
							if using the site\'s semi-transparent theme</strike> 12/8/2006</li>
					<li><strike>Pull thumbnails from database</strike> 1/1/07</li>
					<li><strike>Load thumbnails into carousel with Ajax</strike> 1/15/2007</li>
					<li><strike>Handle enabling and disabling of carousel arrows</strike> 1/15/2007</li>
					<li>Get different arrow images or smooth current images</li>
					<li>Load preview pane from thumbnail click</li>
					<li>Ensure preview pane has lightbox functionality</li>
					<li>Add albums to database</li>
					<li>Load albums from database</li>
					<li>Load thumbnails into carousel from album click</li>
					<li>Create a page that lists the albums as an index</li>
					<li>Clean up gallery stylesheet</li>
				</ul>';
	}
}
?>