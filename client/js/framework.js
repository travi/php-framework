travi.framework = (function () {
    "use strict";

    var ENHANCEMENT_VERSION_KEY = "enhancementVersion",
        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION = 10,
        MOBILE_ENHANCEMENT_VERSION = "mobile",
        DESKTOP_ENHANCEMENT_VERSION = "desktop",

        init = function () {
            if (Modernizr.mq('only screen and (min-width: 481px) and (max-width: 1024px)')) {
                this.cookies.create(
                        ENHANCEMENT_VERSION_KEY,
                        MOBILE_ENHANCEMENT_VERSION,
                        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
                );
            } else {
                this.cookies.create(
                        ENHANCEMENT_VERSION_KEY,
                        DESKTOP_ENHANCEMENT_VERSION,
                        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
                );
            }
        };

    return {
        init    : init
    };
}());
