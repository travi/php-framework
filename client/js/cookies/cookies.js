
travi.framework.cookies = (function () {
    "use strict";

    var getNameAndValueFromPair = function (pair) {
        var nameAndValue = pair.split('=');
        return {
            name: nameAndValue[0],
            value: nameAndValue[1]
        };
    },

    getNameFromPair = function (pair) {
        return getNameAndValueFromPair(pair).name;
    },

    getValueFromCookie = function (pair) {
        return getNameAndValueFromPair(pair).value;
    },

    trimLeadingSpaces = function (pair) {
        while (pair.charAt(0) === ' ') {
            pair = pair.substring(1, pair.length);
        }

        return pair;
    },

    getCookie = function (name) {
        var i, qty, currentPair,
            cookies = document.cookie.split(';');

        qty = cookies.length;
        for (i = 0; i < qty; i = i + 1) {
            currentPair = trimLeadingSpaces(cookies[i]);

            if (name === getNameFromPair(currentPair)) {
                return currentPair;
            }
        }
    },

    getCookies = function () {
        return document.cookie.split(';');
    },

    exists = function (name) {
        return getCookie(name) !== undefined;
    },

    getDateNowAdjustedByDays = function (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        return date;
    },

    valueOf = function (name) {
        return getValueFromCookie(getCookie(name));
    },

    buildExpiresRelativeToNow = function (days) {
        var date = getDateNowAdjustedByDays(days);
        return "; expires=" + date.toGMTString();
    },

    create = function (name, value, days) {
        var expires;
        if (days) {
            expires = buildExpiresRelativeToNow(days);
        } else {
            expires = "";
        }

        document.cookie = name + "=" + value + expires + "; path=/";
    },

    remove = function (name) {
        create(name, "", -1);
    },

    clearAll = function () {
        var cookies = getCookies(),
            qty, i;

        qty = cookies.length;
        for (i = 0; i < qty; i = i + 1) {
            remove(getNameFromPair(cookies[i]));
        }
    };

    return {
        exists: exists,
        create: create,
        remove: remove,
        clearAll: clearAll,
        value: valueOf
    };
}());
