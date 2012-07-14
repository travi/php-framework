travi.test = travi.test || {};

travi.test.common = {
    restore: function (functionName) {
        if (functionName.restore) {
            functionName.restore();
        }
    },

    stubGetTemplates: function () {
        sinon.stub(travi.templates, 'get', function () {
            var deferred = new $.Deferred(),
                promise = deferred.promise();

            deferred.resolve();

            return promise;
        });
    }
};