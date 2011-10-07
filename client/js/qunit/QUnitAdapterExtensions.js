(function () {
    "use strict";



    window.strictEqual = function (actual, expected, msg) {
        if (expected !== actual) {
            fail(msg + 'expected ' + prettyPrintEntity_(expected) + ' but was ' +
                prettyPrintEntity_(actual) + '');
        }
        return true;
    };
}());