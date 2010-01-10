"use strict";

jQuery.fn.alignFields = function () {
	return this.each(function () {
		if (($.browser.mozilla) && (parseFloat($.browser.version) < 1.9)) {
			$(this).find("ul.fieldList li>label").each(function (i) {
				var labelSpan = $('<span>').css('display', 'block')
					.width($(this).width()).html($(this).html());
				$(this).empty().append(labelSpan).css('display', '-moz-inline-box');
			});
		}
	});
};