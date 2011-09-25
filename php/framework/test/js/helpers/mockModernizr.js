(function (global, fwork) {
    "use strict";

    global.Modernizr = fwork.utils.createObjectFrom(fwork.baseMock, {
        mq: function (query) {
            var name = 'mq';
            this.recordCall(name, query);
            return this.expectations[name].returnValue;
        }
    });
}(this, travi.framework));
