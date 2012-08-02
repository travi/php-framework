(function (travi, global) {
    'use strict';

    var amplify = global.amplify;

    function publish() {
        amplify.publish();
    }

    function subscribe() {
        amplify.subscribe();
    }

    function unsubscribe() {
        amplify.unsubscribe();
    }

    travi.namespace('events', {
        publish         : publish,
        subscribe       : subscribe,
        unsubscribe     : unsubscribe
    });
}(travi, this));