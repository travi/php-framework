(function (global, travi) {
    "use strict";

    var refresh = function () {
        global.location.reload();
    };

    travi.namespace('location', {
        refresh: refresh
    });
}(this, travi));
