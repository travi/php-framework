(function () {
    'use strict';

    travi.namespace('test', {
        common: {
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
            },

            assertObjectsHaveSameValues: function (expected, actual) {
                var key;

                for (key in expected) {
                    if (expected.hasOwnProperty(key)) {
                        assertEquals(expected[key], actual[key]);
                    }
                }
            },

            assertAjaxCallMadeWith: function (expected, actual) {
                this.assertObjectsHaveSameValues(expected, actual);
            }
        }
    });
}());