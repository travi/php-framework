travi.framework = (function () {
    "use strict";

    var ENHANCEMENT_VERSION_KEY = "enhancementVersion",
        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION = 10,
        MOBILE_ENHANCEMENT_VERSION = "mobile",
        DESKTOP_ENHANCEMENT_VERSION = "desktop",
        MOBILE_CHOICE = MOBILE_ENHANCEMENT_VERSION,
        DESKTOP_CHOICE = DESKTOP_ENHANCEMENT_VERSION,
        BASIC_CHOICE = 'basic',

        getConstants = function () {
            return {
                ENHANCEMENT_VERSION_KEY: ENHANCEMENT_VERSION_KEY,
                DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION: DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION,
                MOBILE_ENHANCEMENT_VERSION: MOBILE_ENHANCEMENT_VERSION,
                DESKTOP_ENHANCEMENT_VERSION: DESKTOP_ENHANCEMENT_VERSION,
                MOBILE_CHOICE: MOBILE_CHOICE,
                DESKTOP_CHOICE: DESKTOP_CHOICE,
                BASIC_CHOICE: BASIC_CHOICE
            };
        },

        setEnhancementVersionTo = function (versionKey) {
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
                    setEnhancementVersionTo(MOBILE_ENHANCEMENT_VERSION);
                } else {
                    setEnhancementVersionTo(DESKTOP_ENHANCEMENT_VERSION);
                }
            }
        },

        addLinksToChooseVersion = function () {
            var currentEnhancement = travi.framework.cookies.value(ENHANCEMENT_VERSION_KEY),
                alternateEnhancement;

            if (currentEnhancement === MOBILE_ENHANCEMENT_VERSION) {
                alternateEnhancement = DESKTOP_CHOICE;
            } else if (currentEnhancement) {
                alternateEnhancement = MOBILE_CHOICE;
            }

            $('footer').append(
                    '<div id="enhancementVersion">'
                    + '<span id="explainChooseVersion">Switch to version</span>'
                    + '<ul id="versions">'
                    + '<li id="basicVersion">basic</li>'
                    + '<li id="' + alternateEnhancement + 'Version">'
                    + alternateEnhancement
                    + '</li></ul></div>'
            );

            $('#' + DESKTOP_CHOICE + 'Version').click(function () {
                setEnhancementVersionTo(DESKTOP_ENHANCEMENT_VERSION);
            });

            $('#' + MOBILE_CHOICE + 'Version').click(function () {
                setEnhancementVersionTo(MOBILE_ENHANCEMENT_VERSION);
            });
        },

        init = function () {
            setEnhancementVersion();
            addLinksToChooseVersion();
        };

    return {
        init        : init,
        constants   : getConstants
    };
}());
