(function (travi, global) {
    'use strict';

    travi.namespace('events', {
        publish         : global.amplify.publish,
        subscribe       : global.amplify.subscribe,
        unsubscribe     : global.amplify.unsubscribe
    });
}(travi, this));