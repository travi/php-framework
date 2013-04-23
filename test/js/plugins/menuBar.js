travi.test.testCase('MenuBarTests', (function () {
    'use strict';

    return {
        setUp: function () {
            $('body').append('<ul id="menuBar">' +
                '<li>' +
                '   <ul></ul>' +
                '</li>' +
                '<li>' +
                '   <ul></ul>' +
                '</li>' +
                '</ul>');

            $('#menuBar').menuBar();
        },

        'test each menu gets hidden': function () {
            assertFalse($('#menuBar ul').eq(0).is(':visible'));
            assertFalse($('#menuBar ul').eq(1).is(':visible'));
        },

        'test menu plugin applied to each menu': function () {
            assertTrue($('#menuBar ul').eq(0).hasClass('ui-menu'));
            assertTrue($('#menuBar ul').eq(1).hasClass('ui-menu'));
        },

        'test menu is shown on hover and hidden afterward': function () {
            $('#menuBar li').eq(0).mouseenter();

            assertTrue($('#menuBar ul').eq(0).is(':visible'));

            $('#menuBar li').eq(0).mouseleave();

            assertFalse($('#menuBar ul').eq(0).is(':visible'));
        },

        'test indicator icon added': function () {
            var $indicators = $('#menuBar > li > span');
            assertEquals(2, $indicators.length);
            assertTrue($indicators.hasClass('ui-icon'));
            assertTrue($indicators.hasClass('ui-icon-triangle-1-s'));
        }
    };
}()));