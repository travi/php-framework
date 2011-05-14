var $testEnvironmentLoader = null,
    resourcesLocation = 'remote',
    resources = {
        jQuery: {
            local: '/resources/shared/js/jquery/jquery.js',
            remote: 'http://code.jquery.com/jquery-latest.js'
        },
        QUnit: {
            local: '/resources/shared/js/qunit/qunit.js',
            remote: 'http://code.jquery.com/qunit/git/qunit.js'
        },
        mockjax: {
            local: '/resources/shared/js/jquery/plugins/mockjax/jquery.mockjax.js',
            remote: 'http://code.appendto.com/plugins/jquery-mockjax/jquery.mockjax.js'
        },
        templates: {
            local: '/resources/shared/js/jquery/plugins/template/jquery.tmpl.js',
            remote: 'http://ajax.microsoft.com/ajax/jquery.templates/beta1/jquery.tmpl.js'
        },
        travi: '/resources/shared/js/travi.js'
    },
    loadDependencies = function () {
        $LAB
            .script(resources.QUnit[resourcesLocation])
            .script(resources.mockjax[resourcesLocation])
            .script(resources.templates[resourcesLocation])
            .script(resources.travi)
            .wait(loadRunnerDependencies);
    };

loadDependencies();

//$LAB
//    .script(resources.jQuery.remote)
//    .wait(function () {
//        if (typeof window.jQuery === "undefined") {
//           // first load failed, load local fallback, then dependencies
//           $LAB.script(resources.jQuery.local).wait(loadDependencies);
//        } else {
//           // first load was a success, proceed to loading dependencies.
//           loadDependencies();
//        }
//    });





