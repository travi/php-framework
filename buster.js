var config = module.exports;

config["framework tests"] = {
    extensions: [
        require("buster-jstestdriver")
    ],

    rootPath: '../',

    environment: 'browser',

    libs: [
        'include/test/js/stubs/*.js',

        'include/client/thirdparty/jquery/jquery.js',
        'include/client/thirdparty/jquery/plugins/validation/jquery.validate.js',
        'include/client/thirdparty/jquery/**/*.js'
    ],

    sources: [
        'include/client/js/travi.js',
        'include/client/js/travi/templates.js',
        'include/client/js/travi/location.js',
        'include/client/js/travi/events.js',
        'include/client/js/travi/pagination.js',
        'include/client/js/**/*.js'
    ],

    resources: [
        'include/client/templates/**/*.tmpl',
        'include/test/js/templates/**/*.tmpl'
    ],

    testHelpers: [
        'include/test/js/tools/common.js',
        'include/test/js/resources/bootstrap.js'
    ],

    tests: [
        'include/test/js/**/*.js'
    ]
};
