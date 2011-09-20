
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

    getValueFromPair = function (pair) {
        return getNameAndValueFromPair(pair).value;
    },

    trimLeadingSpaces = function (pair) {
        while (pair.charAt(0) === ' ') {
            pair = pair.substring(1, pair.length);
        }

        return pair;
    },

    loopThruCookies = function (callback, cookieNameToMatch) {
        var i, qty, callbackReturnValue, currentPair,
            cookies = document.cookie.split(';');

        qty = cookies.length;
        for (i = 0; i < qty; i = i + 1) {
            currentPair = trimLeadingSpaces(cookies[i]);

            callbackReturnValue = callback.call(null, currentPair, cookieNameToMatch);
            if (callbackReturnValue === true) {
                return true;
            }
        }

        return callbackReturnValue;
    },

    exists = function (name) {
        return loopThruCookies(function (currentPair) {
            if (getNameFromPair(currentPair) === name) {
                return true;
            }
        });
    },

    getDateNowAdjustedByDays = function (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        return date;
    },

    valueOf = function (name) {
        return loopThruCookies(function (pair, nameToMatch) {
            if (nameToMatch === getNameFromPair(pair)) {
                return getValueFromPair(pair);
            }
        }, name);
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
        loopThruCookies(function (cookiePair) {
            remove(getNameFromPair(cookiePair));
        });
    };

    return {
        exists: exists,
        create: create,
        remove: remove,
        clearAll: clearAll,
        value: valueOf
    };
}());
