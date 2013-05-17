travi.test.testCase("PaginationTest", (function () {
    'use strict';

    return {
        pagination: travi.pagination,
        events: travi.events,

        PAGE_LOADED: 'page-loaded',
        HIDDEN_CLASS: 'outOfRange',

        setUp: function () {
            var testCase = this;

            $('body').append($.render.pagination({
                controller: 'controller',
                nextOffset: 34,
                prevOffset: -24
            }));

            sinon.stub(this.events, 'subscribe', function (eventName, callback) {
                if (testCase.PAGE_LOADED === eventName) {
                    testCase.pageLoadedCallback = callback;
                }
            });

            this.pagination.init();

            sinon.stub(this.events, 'publish');
        },

        tearDown: function () {
            travi.test.common.restore([
                this.events.publish,
                this.events.subscribe
            ]);
        },

        'test events defined properly': function () {
            assertObject(this.pagination.events);
            assertEquals('next-page-requested', this.pagination.events.NEXT_PAGE_REQUESTED);
            assertEquals('prev-page-requested', this.pagination.events.PREV_PAGE_REQUESTED);
        },

        'test out-of-range class defined properly': function () {
            assertObject(this.pagination.constants);
            assertEquals('outOfRange', this.pagination.constants.get('HIDDEN_CLASS'));
        },

        'test clicking next-page link triggers proper event': function () {
            var call;

            $('a.more').click();

            sinon.assert.calledOnce(this.events.publish);
            call = this.events.publish.getCall(0);
            assertEquals(
                this.pagination.events.NEXT_PAGE_REQUESTED,
                call.args[0]
            );
            assertEquals('/controller/?offset=34', call.args[1].url);
        },

        'test clicking prev-page link triggers proper event': function () {
            var call;

            $('a.prev').click();

            assert(this.events.publish.calledOnce);
            call = this.events.publish.getCall(0);
            assertEquals(
                this.pagination.events.PREV_PAGE_REQUESTED,
                call.args[0]
            );
            assertEquals('/controller/?offset=-24', call.args[1].url);
        },

        'test links updated properly after page loaded': function () {
            var newPrevOffset = 'newPrev',
                newNextOffset = 'newNext';

            this.pageLoadedCallback({
                nextOffset: newNextOffset,
                prevOffset: newPrevOffset
            });

            assertEquals(0, $('.pagination .' + this.HIDDEN_CLASS).length);
            assertEquals('/controller/?offset=' + newNextOffset, $('a.more').attr('href'));
            assertEquals('/controller/?offset=' + newPrevOffset, $('a.prev').attr('href'));
        },

        'test prev link hidden when at beginning': function () {
            amplify.publish(this.PAGE_LOADED, {
                offset: 0,
                nextOffset: 5,
                prevOffset: -5
            });

            assertTrue($('a.prev').parent().hasClass(this.HIDDEN_CLASS));
            assertTrue($('li.pipeDivider').hasClass(this.HIDDEN_CLASS));
            assertFalse($('a.more').parent().hasClass(this.HIDDEN_CLASS));
        },

        'test next link hidden when at end': function () {
            this.pageLoadedCallback({
                offset: 1,
                nextOffset: 5,
                prevOffset: -5,
                total: 4
            });

            assertTrue($('a.more').parent().hasClass(this.HIDDEN_CLASS));
            assertTrue($('li.pipeDivider').hasClass(this.HIDDEN_CLASS));
            assertFalse($('a.prev').parent().hasClass(this.HIDDEN_CLASS));
        }
    };
}()));