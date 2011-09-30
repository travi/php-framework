travi.framework.location = (function (global) {
    "use strict";

    var refresh = function () {
        global.location.reload();
    };

    return {
        refresh: refresh
    };
}(this));
