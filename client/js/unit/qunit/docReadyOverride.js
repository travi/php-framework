"use strict";

jQuery.ready = function () {
		// Make sure that the DOM is not already loaded
//		if ( !jQuery.isReady ) {
			// Remember that the DOM is ready
			jQuery.isReady = true;

			// If there are functions bound, to execute
			if (jQuery.readyList) {
				// Execute all of them
				jQuery.each(jQuery.readyList, function () {
					this.call(document, jQuery);
				});

				// Reset the list of functions
//				jQuery.readyList = null;
			}

			// Trigger any bound ready events
			jQuery(document).triggerHandler("ready");
//		}
	    };