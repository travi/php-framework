(function (framework) {
    "use strict";

    var events = {
        NEXT_PAGE_REQUESTED: 'next-page-requested',
        PREV_PAGE_REQUESTED: 'prev-page-requested'
    };

    function init() {
        $('ul.pagination a').click(function (e) {
            var $this = $(this),
                eventName;

//            e.preventDefault();
//            e.stopPropagation();

            if ($this.hasClass('more')) {
                eventName = events.NEXT_PAGE_REQUESTED;
            } else {
                eventName = events.PREV_PAGE_REQUESTED;
            }

            framework.publish(eventName, {url: $this.attr('href')});
        });
    }

    $(init);

    framework.namespace('pagination', {
        init: init,
        events: events
    });
}(travi));