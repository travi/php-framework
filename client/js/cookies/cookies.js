
travi.framework.cookies = (function () {
    "use strict";

    var trimLeadingSpaces = function (pair) {
        while (pair.charAt(0) === ' ') {
            pair = pair.substring(1, pair.length);
        }

        return pair;
    },

    loopThruCookies = function (callback) {
        var i, qty, callbackReturnValue, currentPair,
            cookies = document.cookie.split(';');

        qty = cookies.length;
        for (i = 0; i < qty; i = i + 1) {
            currentPair = trimLeadingSpaces(cookies[i]);

            callbackReturnValue = callback.call(null, currentPair);
            if (callbackReturnValue === true) {
                return true;
            }
        }

        return callbackReturnValue;
    },

    exists = function (name) {
        return loopThruCookies(function (currentPair) {
            if (currentPair.indexOf(name + '=') === 0) {
                return true;
            }
        });
    },

    getDateNowAdjustedByDays = function (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        return date;
    },

    getNameAndValueFromPair = function (pair) {
        return pair.split('=');
    },

    getNameFromPair = function (pair) {
        return getNameAndValueFromPair(pair)[0];
    },

    getValueFromPair = function (pair) {
        return getNameAndValueFromPair(pair)[1];
    },

    valueOf = function (name) {
        return loopThruCookies(function (pair) {
            return getValueFromPair(pair);
        });
    },

    create = function (name, value, days) {
        var expires, date;
        if (days) {
            date = getDateNowAdjustedByDays(days);
            expires = "; expires=" + date.toGMTString();
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
            var cookieName = getNameFromPair(cookiePair);
            remove(cookieName);
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
