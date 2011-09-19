
travi.framework.cookies = (function () {
    "use strict";

    var trimLeadingSpaces = function (currentPair) {
        while (currentPair.charAt(0) === ' ') {
            currentPair = currentPair.substring(1, currentPair.length);
        }

        return currentPair;
    },

    loopThruCookies = function (callback) {
        var i, qty, bool, currentPair,
            cookies = document.cookie.split(';');

        qty = cookies.length;
        for (i = 0; i < qty; i = i + 1) {
            currentPair = trimLeadingSpaces(cookies[i]);

            bool = callback.call(null, currentPair);
            if (bool === true) {
                return true;
            }
        }

        return false;
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

    getNameFromPair = function (pair) {
        var nameAndValue = pair.split('=');
        return nameAndValue[0];
    },

    valueOf = function (name) {

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
        clearAll: clearAll
    };
}());
