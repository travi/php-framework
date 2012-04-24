(function (travi) {
    "use strict";

    var templates = travi.templates,
        cookies = travi.cookies,
        location = travi.location,

        templateName = 'chooseEnhancement',

        ENHANCEMENT_VERSION_KEY = "enhancementVersion",
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
            cookies.create(
                ENHANCEMENT_VERSION_KEY,
                versionKey,
                DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
            );
            location.refresh();
        },

        resetVersion = function () {
            cookies.remove(ENHANCEMENT_VERSION_KEY);
            location.refresh();
        },

        detectEnhancementVersion = function () {
            if (Modernizr.mq('only screen and (min-width: 320px) and (max-width: 480px)')) {
                setEnhancementVersionTo(MOBILE_ENHANCEMENT_VERSION);
            } else {
                setEnhancementVersionTo(DESKTOP_ENHANCEMENT_VERSION);
            }
        },

        setInitialEnhancementVersion = function () {
            if (!cookies.exists(ENHANCEMENT_VERSION_KEY)) {
                detectEnhancementVersion(setEnhancementVersionTo, MOBILE_ENHANCEMENT_VERSION, DESKTOP_ENHANCEMENT_VERSION);
            }
        },

        addLinksToChooseVersion = function () {
            var currentEnhancement = cookies.value(ENHANCEMENT_VERSION_KEY),
                alternateEnhancement;

            if (currentEnhancement === MOBILE_ENHANCEMENT_VERSION) {
                alternateEnhancement = DESKTOP_CHOICE;
            } else if (currentEnhancement === DESKTOP_ENHANCEMENT_VERSION) {
                alternateEnhancement = MOBILE_CHOICE;
            }

            templates.get(templateName).then(function () {
                $('footer').append($.render({
                    alternateEnhancement: alternateEnhancement
                }, templateName));

                $('#' + DESKTOP_CHOICE + 'Version').click(function () {
                    setEnhancementVersionTo(DESKTOP_ENHANCEMENT_VERSION);
                });

                $('#' + MOBILE_CHOICE + 'Version').click(function () {
                    setEnhancementVersionTo(MOBILE_ENHANCEMENT_VERSION);
                });

                $('#detectVersion').click(resetVersion);
            });
        },

        init = function () {
            setInitialEnhancementVersion();
            $(addLinksToChooseVersion);
        };

    travi.namespace('framework', {
        init: init,
        namespace: travi.namespace
    });

    init();
}(travi));
