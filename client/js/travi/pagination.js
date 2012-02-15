(function (framework) {
    "use strict";

    var constants = travi.constants,

        $paginationControls,
        $divider,
        $moreLink,
        $prevLink,

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
        $link.attr('href', $link.attr('href').replace(/offset=\d*/, 'offset=' + newOffset));
    }

    function updateLinkVisibility(eventData) {
        var HIDDEN_CLASS = constants.get('HIDDEN_CLASS');

        $paginationControls.find('li').removeClass(HIDDEN_CLASS);

        if (eventData.offset === 0) {
            $prevLink.parent().addClass(HIDDEN_CLASS);
            $divider.addClass(HIDDEN_CLASS);
        }
        if (eventData.total < eventData.nextOffset) {
            $moreLink.parent().addClass(HIDDEN_CLASS);
            $divider.addClass(HIDDEN_CLASS);
        }
    }

    function updateLinks(eventData) {
        updateOffset($moreLink, eventData.nextOffset);
        updateOffset($prevLink, eventData.prevOffset);

        updateLinkVisibility(eventData);
    }

    function init() {
        $paginationControls = $('ul.pagination');
        $divider = $paginationControls.find('li.pipeDivider');
        $moreLink = $paginationControls.find('a.more');
        $prevLink = $paginationControls.find('a.prev');

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