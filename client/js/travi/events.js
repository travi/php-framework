(function (travi, global) {
    'use strict';

    var amplify = global.amplify;

    function publish(eventName, data) {
        amplify.publish(eventName, data);
    }

    function subscribe(eventName, callback) {
        amplify.subscribe(eventName, callback);
    }

    function unsubscribe(eventName, callback) {
        amplify.unsubscribe(eventName, callback);
    }

    travi.namespace('events', {
        publish         : publish,
        subscribe       : subscribe,
        unsubscribe     : unsubscribe
    });
}(travi, this));