travi.framework = (function () {
    "use strict";

    var ENHANCEMENT_VERSION_KEY = "enhancementVersion",
        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION = 10,
        MOBILE_ENHANCEMENT_VERSION = "mobile",
        DESKTOP_ENHANCEMENT_VERSION = "desktop",

        setEnhancementVersion = function () {
            if (!travi.framework.cookies.exists(ENHANCEMENT_VERSION_KEY)) {
                if (Modernizr.mq('only screen and (min-width: 320px) and (max-width: 480px)')) {
                    travi.framework.cookies.create(
                            ENHANCEMENT_VERSION_KEY,
                            MOBILE_ENHANCEMENT_VERSION,
                            DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
                    );
                } else {
                    travi.framework.cookies.create(
                            ENHANCEMENT_VERSION_KEY,
                            DESKTOP_ENHANCEMENT_VERSION,
                            DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
                    );
                }
            }
        },

        init = function () {
            setEnhancementVersion();
        };

    return {
        init    : init
    };
}());
