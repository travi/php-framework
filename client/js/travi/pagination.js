(function (framework) {
    "use strict";

    var constants = travi.constants,

        $paginationControls,

        events = {
            NEXT_PAGE_REQUESTED: 'next-page-requested',
            PREV_PAGE_REQUESTED: 'prev-page-requested'
        };

    constants.set('HIDDEN_CLASS', 'outOfRange');

    function handleInteraction(e) {
        var $this = $(e.target),
            eventName;

        e.preventDefault();
        e.stopPropagation();

        if ($this.hasClass('more')) {
            eventName = events.NEXT_PAGE_REQUESTED;
        } else {
            eventName = events.PREV_PAGE_REQUESTED;
        }

        framework.publish(eventName, {url: $this.attr('href')});
    }

    function updateOffset($link, newOffset) {
        $link.attr('href', $link.attr('href').replace(/offset=.*/, 'offset=' + newOffset));
    }

    function updateLinks(eventData) {
        updateOffset($paginationControls.find('a.more'), eventData.nextOffset);
        updateOffset($paginationControls.find('a.prev'), eventData.prevOffset);

        $paginationControls.find('li').removeClass(constants.get('HIDDEN_CLASS'));
    }

    function init() {
        $paginationControls = $('ul.pagination');

        $paginationControls.find('a').click(handleInteraction);

        travi.subscribe('page-loaded', updateLinks);
    }

    $(init);

    framework.namespace('pagination', {
        init: init,
        events: events,
        constants: constants,
        update: updateLinks
    });
}(travi));