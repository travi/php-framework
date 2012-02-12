(function (framework) {
    "use strict";

    var events = {
        NEXT_PAGE_REQUESTED: 'next-page-requested',
        PREV_PAGE_REQUESTED: 'prev-page-requested'
    };

    function init() {
        $('ul.pagination a').click(function () {
            var $this = $(this),
                eventName;

            if ($this.hasClass('more')) {
                eventName = events.NEXT_PAGE_REQUESTED;
            } else {
                eventName = events.PREV_PAGE_REQUESTED;
            }

            framework.publish(eventName);
        });
    }

    $(init);

    framework.namespace('pagination', {
        init: init,
        events: events
    });
}(travi));