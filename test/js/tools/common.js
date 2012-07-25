travi.test = travi.test || {};

travi.test.common = {
    restore: function (toRestore) {
        function restoreFunction(functionName) {
            if (functionName.restore) {
                functionName.restore();
            }
        }

        if ($.isArray(toRestore)) {
            $(toRestore).each(function () {
                restoreFunction(this);
            });
        } else {
            restoreFunction(toRestore);
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