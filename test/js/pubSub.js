travi.test.testCase('PubSubTests', (function () {
    'use strict';

    return {
        common: travi.test.common,
        events: travi.events,

        eventName: 'some-event',
        callback: function () {
        },
        data: {},

        tearDown: function () {
            this.common.restore([
                amplify.publish,
                amplify.subscribe,
                amplify.unsubscribe
            ]);
        },

        'test publish mapped to amplify': function () {
            sinon.spy(amplify, 'publish');

            this.events.publish(this.eventName, this.data);

            assert.calledOnce(amplify.publish);
            assert.calledWith(amplify.publish, this.eventName, this.data);
        },

        'test subscribe mapped to amplify': function () {
            sinon.spy(amplify, 'subscribe');

            this.events.subscribe(this.eventName, this.callback);

            assert.calledOnce(amplify.subscribe);
            assert.calledWith(amplify.subscribe, this.eventName, this.callback);
        },

        'test unsubscribe mapped to amplify': function () {
            sinon.spy(amplify, 'unsubscribe');

            this.events.unsubscribe(this.eventName, this.callback);

            assert.calledOnce(amplify.unsubscribe);
            assert.calledWith(amplify.unsubscribe, this.eventName, this.callback);
        }
    };
}()));