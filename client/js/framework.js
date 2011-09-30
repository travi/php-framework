travi.framework = (function () {
    "use strict";

    var ENHANCEMENT_VERSION_KEY = "enhancementVersion",
        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION = 10,
        MOBILE_ENHANCEMENT_VERSION = "mobile",
        DESKTOP_ENHANCEMENT_VERSION = "desktop",

        setEnhancementVersionCookieTo = function (versionKey) {
            travi.framework.cookies.create(
                ENHANCEMENT_VERSION_KEY,
                versionKey,
                DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
            );
            travi.framework.location.refresh();
        },

        setEnhancementVersion = function () {
            if (!travi.framework.cookies.exists(ENHANCEMENT_VERSION_KEY)) {
                if (Modernizr.mq('only screen and (min-width: 320px) and (max-width: 480px)')) {
                    setEnhancementVersionCookieTo(MOBILE_ENHANCEMENT_VERSION);
                } else {
                    setEnhancementVersionCookieTo(DESKTOP_ENHANCEMENT_VERSION);
                }
            }
        },

        addLinksToChooseVersion = function () {
            var currentEnhancement = travi.framework.cookies.value(ENHANCEMENT_VERSION_KEY),
                alternateEnhancement;

            if (currentEnhancement === MOBILE_ENHANCEMENT_VERSION) {
                alternateEnhancement = DESKTOP_ENHANCEMENT_VERSION;
            } else if (currentEnhancement) {
                alternateEnhancement = MOBILE_ENHANCEMENT_VERSION;
            }

            $('footer').append(
                    '<ul id="enhancementVersion">'
                    + '<li id="basicVersion">basic</li>'
                    + '<li id="' + alternateEnhancement + 'Version">'
                    + alternateEnhancement
                    + '</li></ul>'
            );

            $('#' + DESKTOP_ENHANCEMENT_VERSION + 'Version').click(function () {
                setEnhancementVersionCookieTo(DESKTOP_ENHANCEMENT_VERSION);
            });

            $('#' + MOBILE_ENHANCEMENT_VERSION + 'Version').click(function () {
                setEnhancementVersionCookieTo(MOBILE_ENHANCEMENT_VERSION);
            });
        },

        init = function () {
            setEnhancementVersion();
            addLinksToChooseVersion();
        };

    return {
        init    : init
    };
}());
