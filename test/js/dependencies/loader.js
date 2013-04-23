travi.test.testCase('DependencyLoaderTests', (function () {
    'use strict';

    return {
        common: travi.test.common,

        loader: travi.dependencies.loader,

        simpleNamespace: 'module',
        script: 'some.namespaced.module',
        pathToScript: '/resources/js/some/namespaced/module',

        setUp: function () {
            var testCase = this;

            sinon.stub($, 'getScript', function (url, callback) {
                testCase.moduleLoadedCallback = callback;
            });

            this.loader.init();
        },

        tearDown: function () {
            if (travi[this.simpleNamespace]) {
                travi[this.simpleNamespace] = null;
            }

            this.common.restore([
                $.getScript
            ]);
        },

        'test module requested from server if not already loaded': function () {
            this.loader.load(this.script);

            assert.calledOnce($.getScript);
            assert.calledWith($.getScript, this.pathToScript);
        },

        'test module not requested from server if already loaded': function () {
            this.loader.load(this.script);
            $.getScript.reset();

            this.loader.load(this.script);

            refute.called($.getScript);
        },

        'test module not requested from server if namespace already exists': function () {
            var promise = this.loader.load('travi.' + this.simpleNamespace);

            travi[this.simpleNamespace] = {};

            refute.called($.getScript);
            assertTrue(promise.isResolved());
        },

        'test promise returned to allow for async loading': function () {
            var promise = this.loader.load(this.script);

            assertObject(promise);
            assertFunction(promise.promise);

            assertFalse(promise.isResolved());
        },

        'test promise is resolved once the module has loaded from the server': function () {
            var promise = this.loader.load(this.script);

            this.moduleLoadedCallback();

            assertTrue(promise.isResolved());
        },

        'test original promise returned for module that has already been loaded/requested': function () {
            var originalPromise = this.loader.load(this.script),
                promise = this.loader.load(this.script);

            assertSame(originalPromise, promise);
        }
    };
}()));