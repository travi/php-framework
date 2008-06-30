jQuery.fn.alignFields = function() {
	return this.each(function(){
		if($.browser.mozilla){
			$(this).find("ul.fieldList label, ul.fieldList .formBlock").each( function(i){
				var labelSpan = $('<span>').css('display','block')
					.width($(this).width()).html($(this).html());
				$(this).empty().append(labelSpan).css('display','-moz-inline-box');
			} );
		}
	});
};