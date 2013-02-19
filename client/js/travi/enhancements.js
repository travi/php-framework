travi.templates.preLoad('chooseEnhancement', '/resources/shared/templates/enhancementVersion.tmpl');

(function (travi) {
    "use strict";

    var templates = travi.templates,
        cookies = travi.cookies,
        location = travi.location,

        ENHANCEMENT_VERSION_KEY = "enhancementVersion",
        DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION = 10,
        MOBILE_ENHANCEMENT_VERSION = 'small',
        DESKTOP_ENHANCEMENT_VERSION = 'large',
        SMALL_SCREEN_CHOICE = MOBILE_ENHANCEMENT_VERSION,
        LARGE_SCREEN_CHOICE = DESKTOP_ENHANCEMENT_VERSION,
        BASIC_CHOICE = 'basic',
        SMALL_COOKIE_VALUE = 's',
        LARGE_COOKIE_VALUE = 'l';

    function setEnhancementVersionTo(versionKey) {
        cookies.create(
            ENHANCEMENT_VERSION_KEY,
            versionKey,
            DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION
        );
        location.refresh();
    }

    function resetVersion() {
        cookies.remove(ENHANCEMENT_VERSION_KEY);
        location.refresh();
    }

    function detectEnhancementVersion() {
        if (Modernizr.mq('only screen and (min-width: 320px) and (max-width: 480px)')) {
            setEnhancementVersionTo(SMALL_COOKIE_VALUE);
        } else {
            setEnhancementVersionTo(LARGE_COOKIE_VALUE);
        }
    }

    function setInitialEnhancementVersion() {
        if (!cookies.exists(ENHANCEMENT_VERSION_KEY)) {
            detectEnhancementVersion();
        }
    }

    function addLinksToChooseVersion() {
        var currentEnhancement = cookies.value(ENHANCEMENT_VERSION_KEY),
            alternateEnhancement,
            alternateEnhancementText;

        if (currentEnhancement === SMALL_COOKIE_VALUE) {
            alternateEnhancement = LARGE_SCREEN_CHOICE;
            alternateEnhancementText = 'large screen';
        } else if (currentEnhancement === LARGE_COOKIE_VALUE) {
            alternateEnhancement = SMALL_SCREEN_CHOICE;
            alternateEnhancementText = 'small screen';
        }

        templates.render('chooseEnhancement', {
            alternateEnhancement: alternateEnhancement,
            alternateEnhancementText: alternateEnhancementText
        }, function (renderedTemplate) {
            $('footer').append(renderedTemplate);

            $('#' + LARGE_SCREEN_CHOICE + 'Version').click(function () {
                setEnhancementVersionTo(LARGE_COOKIE_VALUE);
            });

            $('#' + SMALL_SCREEN_CHOICE + 'Version').click(function () {
                setEnhancementVersionTo(SMALL_COOKIE_VALUE);
            });

            $('#detectVersion').click(resetVersion);
        });
    }

    function getConstants() {
        return {
            ENHANCEMENT_VERSION_KEY: ENHANCEMENT_VERSION_KEY,
            DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION: DAYS_BEFORE_ENHANCEMENT_COOKIE_EXPIRATION,
            MOBILE_ENHANCEMENT_VERSION: MOBILE_ENHANCEMENT_VERSION,
            DESKTOP_ENHANCEMENT_VERSION: DESKTOP_ENHANCEMENT_VERSION,
            MOBILE_CHOICE: SMALL_SCREEN_CHOICE,
            DESKTOP_CHOICE: LARGE_SCREEN_CHOICE,
            BASIC_CHOICE: BASIC_CHOICE,
            LARGE_COOKIE_VALUE: LARGE_COOKIE_VALUE,
            SMALL_COOKIE_VALUE: SMALL_COOKIE_VALUE
        };
    }

    function init() {
        setInitialEnhancementVersion();
        $(addLinksToChooseVersion);
    }

    travi.namespace('enhancements', {
        constants: getConstants,
        init: init
    });

    init();
}(travi));