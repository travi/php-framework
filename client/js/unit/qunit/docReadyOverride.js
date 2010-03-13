"use strict";

jQuery.ready = function () {
			// Remember that the DOM is ready
			jQuery.isReady = true;

			// If there are functions bound, to execute
			if (jQuery.readyList) {
				// Execute all of them
				jQuery.each(jQuery.readyList, function () {
					this.call(document, jQuery);
				});
			}

			// Trigger any bound ready events
			jQuery(document).triggerHandler("ready");
	    };