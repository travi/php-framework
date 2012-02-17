(function (global, framework) {
    "use strict";

    var refresh = function () {
        global.location.reload();
    };

    framework.namespace('location', {
        refresh: refresh
    });
}(this, travi.framework));
