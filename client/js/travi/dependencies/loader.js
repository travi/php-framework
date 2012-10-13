(function (travi, global) {
    "use strict";

    var PATH_TO_MODULES = '/resources/js/',

        loaded;

    function moduleAlreadyDefined(module) {
        var levels = module.split('.'),
            levelCount = levels.length,
            currentLevel = global,
            i;

        try {
            for (i = 0; i < levelCount; i += 1) {
                currentLevel = currentLevel[levels[i]];
            }
        } catch (e) {
            return false;
        }

        return true;
    }

    function load(module) {
        var deferred = new $.Deferred(),
            promise = deferred.promise(),
            pathToModule = module.replace(/\./g, '/');

        loaded.js[module] = promise;

        if (moduleAlreadyDefined(module)) {
            deferred.resolve();
        } else {
            $.getScript(PATH_TO_MODULES + pathToModule, function () {
                deferred.resolve();
            });
        }

        return promise;
    }

    function get(module) {
        var promise = loaded.js[module];

        if (promise) {
            return promise;
        } else {
            return load(module);
        }
    }

    function init() {
        loaded = {
            js: []
        };
    }

    travi.namespace('dependencies.loader', {
        load: get,
        init: init
    });

    init();
}(travi, this));