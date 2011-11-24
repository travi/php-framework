(function () {
    "use strict";

    TestCase("PaginationTest", {
        setUp: function () {
            $('body').append('<div id="scratch" class="test">');
        },

        "test request for more items loads more items into list": function () {
            assertEquals(1, $('#scratch').length);
        }
    });
}());